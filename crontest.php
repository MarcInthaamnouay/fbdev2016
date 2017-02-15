#!/usr/bin:/bin
<?php
	require 'entity/Contest.php';
	require 'service/helper.php';
	require 'service/fb.php';
	require 'vendor/autoload.php';

	$contest = new Contest();
	$currentContest = $contest->getCurrentContest(); //id,start,end,title,text,lot
	if(!empty($currentContest)) {
		if($currentContest['active'] == 1) { 
			// On est dans le dernier concours en cours
			if(new DateTime($currentContest['end']) < new DateTime() ) {// Inverser symbole pour le test
				// Le concours est terminé
				$allparticipants = $contest->getParticipationsOfContest($currentContest['id']);
				$participants = $allparticipants;
				usort($allparticipants, "cmp");

				// La photo gagnante
				// @participants tous les participants
				foreach ($participants as $key => $participant) {
					$idParticipant = $participant['id_user'];
					// L'identifiant de chaque participant
					doWallPost($idParticipant,'Concours','Le concours '.$currentContest['title'].' est terminé, voici la photo gagnante !',$allparticipants[0]['id_picture'],'','Le concours '.$currentContest['title'].' est terminé, voici la photo gagnante !');
				}



				//desactiver le concours. 
				$contest->disactivateContest($currentContest['id']);
			}
			




		}
	}




function doWallPost($userID='', $postName='',$postMessage='',$postLink='',$postCaption='',$postDescription='')
{
	$fb_page_id= '1418106458217541';
	$fb_secret= '951fc8f75cad3716a15efd1f4f053647';
    $token = Helper::retrieveToken($userID);
    $fb_app_url  = 'https://berseck.fbdev.fr/';
    $fb_app_baseUrl ="https://berseck.fbdev.fr/";


	$msg =  array(
	    'message' => '--date: ' . date('Y-m-d') . ' time: ' . date('H:i')
	);

	//construct the message/post by posted data
	$link = "ii";
	$msg['message'] = $postMessage;
	$msg['link'] = $postLink;
	$msg['picture'] = $postLink;
	$msg['name'] = $postName;
	$msg['caption'] = $postCaption;
	$msg['description'] = $postDescription;
	$msg['actions'] = json_encode(array( array('name' => $postName, 'link' => $postLink)));


    try {
        $fbReq = new FacebookServices('/'.$userID.'/feed', $token,'POST', $msg);
        $res = $fbReq->make();

    } catch (FacebookApiException $e) {
        print_r($e);
    }

    return $res;
}

function cmp($a, $b)
{
    if ($a['vote'] == $b['vote']) {
        return 0;
    }
    return ($a['vote'] > $b['vote']) ? -1 : 1;
}
