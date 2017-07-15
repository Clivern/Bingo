<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\BiGroup as BiGroupModel;
use AppBundle\Entity\BiUser as BiUserModel;

class GroupController extends Controller
{

    private $user_repo;
    private $group_repo;
    private $response;


    private function config()
    {
        $this->user_repo = $this->getDoctrine()->getRepository('AppBundle:BiUser');
        $this->group_repo = $this->getDoctrine()->getRepository('AppBundle:BiGroup');
        $this->response = [
        	'status' => 'error',
        	'message' => "",
       	];
    }

    /**
     * @Route("/api/group/add", name="api_group_add_action")
     * @Method({"POST"})
     */
    public function createGroupAction(Request $request)
    {
    	$this->config();
        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##	                   to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###

    	if( $this->group_repo->findOneByName($request->request->get('name', "")) ){
			$this->response["status"] = "error";
			$this->response["message"] = "Group name already used.";
            return new JsonResponse($this->response);
    	}

        $user = $this->user_repo->findOneById($request->request->get('user_id', ""));

        if( !$user ){
            $this->response["status"] = "error";
            $this->response["message"] = "User not exist.";
            return new JsonResponse($this->response);
        }

        $group = new BiGroupModel();
        $group->setName($request->request->get('name', ""));
        $group->setDescription($request->request->get('description', ""));
        $group->setUser($user);
        $group->setCreatedAt(new \DateTime("now"));
        $group->setUpdatedAt(new \DateTime("now"));
        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

		$result = $group->getId();

		if( $result ){
			$this->response["status"] = "success";
			$this->response["message"] = "Group created successfully.";
		}

        return new JsonResponse($this->response);
    }

    /**
     * @Route("/api/group/delete", name="api_group_delete_action")
     * @Method({"POST"})
     */
    public function deleteGroupAction(Request $request)
    {

    	$this->config();
        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##	                   to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###

        $found = $this->group_repo->findOneById($request->request->get('id', ""));

        if( !$found ){
            $this->response["status"] = "error";
            $this->response["message"] = "Group not found.";
            return new JsonResponse($this->response);
        }

        if( !empty($found->getUsers()->toArray()) ){
            $this->response["status"] = "error";
            $this->response["message"] = "Group already has users.";
            return new JsonResponse($this->response);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($found);
        $em->flush();

		$this->response["status"] = "success";
		$this->response["message"] = "Group deleted successfully.";

        return new JsonResponse($this->response);
    }
}
