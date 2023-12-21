<?php
class UNL_ENews_Newsletter_Preview extends UNL_ENews_LoginRequired
{
    /**
     * The newsletter
     *
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;

    public $unpublished_stories;

    public $reusable_stories;

    function __postConstruct()
    {
        if (isset($this->options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($this->options['id']);
        } else {
            $this->newsletter = UNL_ENews_Newsletter::getLastModified();
        }
        if (!empty($_POST)) {
            $this->handlePost();
        }
        $this->unpublished_stories = new UNL_ENews_StoryList_Filter_ByPresentationType(
            new UNL_ENews_Newsroom_UnpublishedStories(array(
                'status'      => 'approved',
                'date'        => $this->newsletter->release_date,
                'newsroom_id' => UNL_ENews_Controller::getUser(true)->newsroom->id,
                'limit'       => -1
            )),
            'news'
        );
        $this->reusable_stories = new UNL_ENews_StoryList_Filter_ByPresentationType(
            new UNL_ENews_Newsroom_ReusableStories(array(
                'status'      => 'approved',
                'date'        => $this->newsletter->release_date,
                'newsroom_id' => UNL_ENews_Controller::getUser(true)->newsroom->id,
                'newsletter_id' => $this->newsletter->id,
                'limit'       => -1
            )),
            'news'
        );
    }

    function handlePost()
    {
        $this->filterPostValues();
        switch($_POST['_type']) {
            case 'addstory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data, you must set the story_id', 400);
                }
                if (is_array($_POST['story_id'])) {
                    foreach ($_POST['story_id'] as $id => $def) {
                        $this->addStory($id, $def['sort_order']);
                    }
                } else {
                    $this->addStory($_POST['story_id'], $_POST['sort_order'], $_POST['intro']);
                }
                break;
            case 'setpresentation':
                if (!isset($_POST['story_id'], $_POST['presentation_id'])) {
                    throw new Exception('invalid request', 400);
                }
                $this->setPresentation($_POST['story_id'], $_POST['presentation_id']);
                break;
            case 'removestory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data, you must set the story_id', 400);
                }
                $this->removeStory($_POST['story_id']);
                break;
            case 'newsletter':
                // Set default value for ready to release to 0
                if (!isset($_POST['ready_to_release']) || $_POST['ready_to_release'] !== '1') {
                    $_POST['ready_to_release'] = '0';
                }

                // Default value time
                $release_hour = '7';
                $release_minute = '00';
                $release_am_pm = 'am';

                // Validate time data
                if (
                    isset($_POST['release_date_hour'])
                    && in_array(
                        $_POST['release_date_hour'],
                        array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12')
                    )
                ) {
                    $release_hour = $_POST['release_date_hour'];
                }
                if (
                    isset($_POST['release_date_minute'])
                    && in_array($_POST['release_date_minute'], array('00', '30'))
                ) {
                    $release_minute = $_POST['release_date_minute'];
                }
                if (
                    isset($_POST['release_date_am_pm'])
                    && in_array($_POST['release_date_am_pm'], array('am', 'pm'))
                ) {
                    $release_am_pm = $_POST['release_date_am_pm'];
                }

                $this->newsletter->synchronizeWithArray($_POST);

                // Set the release date with the time
                $formatted_release_date = $_POST['release_date']
                    . ' '
                    . $release_hour
                    . ':'
                    . $release_minute
                    . ' '
                    . $release_am_pm;
                $this->newsletter->release_date = date('Y-m-d H:i:s', strtotime($formatted_release_date));
                $this->newsletter->save();
                UNL_ENews_Controller::redirect($this->getURL());
                break;
            case 'addnewsletteremail':
                $email = UNL_ENews_Newsroom_Email::getByID($_POST['newsroom_email_id']);
                if ($email->newsroom_id != $this->newsletter->newsroom->id) {
                    throw new Exception('You cannot add an email from another newsroom', 403);
                }
                $this->newsletter->addEmail($email);
                break;
            case 'removenewsletteremail':
                $email = UNL_ENews_Newsletter_Email::getByID($_POST['newsletter_id'], $_POST['newsroom_email_id']);
                if ($email->newsletter_id != $this->newsletter->id) {
                    throw new Exception('You cannot remove an email from another newsroom', 403);
                }
                $this->newsletter->removeEmail($email);
                break;
        }
        // no response is needed (AJAX'd)
        exit();
    }

    function filterPostValues()
    {
        unset($_POST['newsroom_id']);
    }

    function removeStory($story_id)
    {
        if ($story = UNL_ENews_Story::getById($story_id)) {
            return $this->newsletter->removeStory($story);
        }
        return true;
    }

    function addStory($story_id, $sort_order = null, $intro = null)
    {
        if ($story = UNL_ENews_Story::getById($story_id)) {
            return $this->newsletter->addStory($story, $sort_order, $intro);
        }
        throw new Exception('could not add the story to the newsletter', 500);
    }

    function setPresentation($story_id, $presentation_id)
    {
        if ($story = UNL_ENews_Newsletter_Story::getById($this->newsletter->id, $story_id)) {
            $story->setPresentation($presentation_id);
            return $story->save();
        }
        throw new Exception('Could not save presentation', 500);
    }

    function getURL()
    {
        return $this->newsletter->getEditURL();
    }
}
