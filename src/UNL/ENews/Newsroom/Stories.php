<?php
class UNL_ENews_Newsroom_Stories extends UNL_ENews_StoryList
{
    public $options = array('offset' => 0,
                            'limit'  => 30);
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_stories.story_id FROM newsroom_stories, stories ';
        $sql .= 'WHERE newsroom_stories.newsroom_id = '.(int)$options['newsroom_id'] .
                ' AND newsroom_stories.status = \''.$options['status'].'\' AND newsroom_stories.story_id = stories.id';
        switch($options['status']) {
            case 'archived':
                $sql .= ' ORDER BY stories.date_submitted DESC';
                break;
        }
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories, $this->options['offset'], $this->options['limit']);
    }

    public static function relationshipExists($newsroom_id,$story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_id,story_id FROM newsroom_stories ';
        $sql .= 'WHERE newsroom_id = '.(int)$newsroom_id .
                ' AND story_id = '.(int)$story_id;
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                $mysqli->close();
                return true;
            }
        }
        $mysqli->close();
        return false;
    }
}