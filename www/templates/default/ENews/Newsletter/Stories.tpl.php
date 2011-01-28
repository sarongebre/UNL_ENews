<?php
$columns = array();
$adAreas = array();
foreach ($context as $key=>$story) {
    $areaPtr =& $columns;
    if ($story->getPresentation()->type == 'ad') {
        $areaPtr =& $adAreas;
    }

    $areaPtr[$story->sort_order % 3][] = $story;
}
?>
<tr>
    <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
        <!-- This is the main content -->
        <div id="newsColumnIntro" class="newsColumn">
        <?php if (isset($columns[1])): ?>
        <?php foreach ($columns[1] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <div class="story-content">
                    <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </td>
    </tr>
    <tr id="newsStories">
     <td valign="top" width="273">
        <div id="newsColumn1" class="newsColumn">
        <?php if (isset($columns[2])): ?>
        <?php foreach ($columns[2] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <div class="story-content">
                    <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </td>
    <td width="10">&nbsp;</td>
    <td valign="top" width="273">
        <div id="newsColumn2" class="newsColumn">
        <?php if (isset($columns[0])): ?>
        <?php foreach ($columns[0] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <div class="story-content">
                    <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </td>
</tr>
<tr>
	<td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
		<div id="adAreaIntro" class="adArea">
		<?php if (!empty($adAreas[1])): ?>
		<?php foreach ($adAreas[1] as $story): // Intentional FOREACH ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
            	<div class="story-content">
			        <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
			    </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
		<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
		<div id="adArea1" class="adArea">
		<?php if (!empty($adAreas[2])): ?>
		<?php foreach ($adAreas[2] as $story): // Intentional FOREACH ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
				<div class="story-content">
                    <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
		<?php endforeach; ?>
        <?php endif; ?>
		</div>
	</td>
	<td width="10">&nbsp;</td>
	<td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
		<div id="adArea2" class="adArea">
		<?php if (!empty($adAreas[0])): ?>
		<?php foreach ($adAreas[0] as $story): // Intentional FOREACH ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <div class="story-content">
                    <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                </div>
            </div>
            <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
		<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</td>
</tr>