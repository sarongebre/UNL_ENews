<?php
class UNL_ENews_Newsletter_Preview extends UNL_ENews_LoginRequired
{
    /**
     * The newsletter
     * 
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;
    
    public $available_stories;
    
    function __postConstruct()
    {
        if (isset($this->options['newsletter_id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($this->options['newsletter_id']);
        } else {
            $this->newsletter = UNL_ENews_Newsletter::getLastModified();
        }
        if (!empty($_POST)) {
            $this->handlePost();
        }
        $this->available_stories = new UNL_ENews_Newsroom_Stories(array('status'      => 'approved',
                                                                        'newsroom_id' => UNL_ENews_Controller::getUser(true)->newsroom->id));
    }
    
    function handlePost()
    {
        switch($_POST['_type']) {
            case 'addstory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data');
                }
                $this->addStory($_POST['story_id']);
                break;
            case 'removestory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data');
                }
                $this->removeStory($_POST['story_id']);
                break;
        }
    }
    
    function addStory($story_id)
    {
        if ($story = UNL_ENews_Story::getById($story_id)) {
            return $this->newsletter->addStory($story);
        }
        throw new Exception('could not add the story to the newsletter');
    }
}