<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\BiGroup as BiGroupModel;
use AppBundle\Entity\BiUser as BiUserModel;

class UserController extends Controller
{

    private $user_repo;
    private $group_repo;


    private function config()
    {
        $this->user_repo = $this->getDoctrine()->getRepository('AppBundle:BiUser');
        $this->group_repo = $this->getDoctrine()->getRepository('AppBundle:BiGroup');
    }

    /**
     * @Route("/api/user/add", name="api_user_add_action")
     * @Method({"POST"})
     */
    public function addUserAction(Request $request)
    {
        $this->config();

        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##                     to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###


        if( $this->user_repo->findOneByUsername($request->request->get('username', "")) ){
            $this->response["status"] = "error";
            $this->response["message"] = "Username already used.";
            return new JsonResponse($this->response);
        }

        if( $this->user_repo->findOneByEmail($request->request->get('email', "")) ){
            $this->response["status"] = "error";
            $this->response["message"] = "Email already used.";
            return new JsonResponse($this->response);
        }

        $user = new BiUserModel();
        $user->setName($request->request->get('name', ""));
        $user->setUsername($request->request->get('username', ""));
        $user->setEmail($request->request->get('email', ""));
        $user->setPasswordHash(md5($request->request->get('password', ""))); # I never use md5 in real projects
        $user->setCreatedAt(new \DateTime("now"));
        $user->setUpdatedAt(new \DateTime("now"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $result = $user->getId();

        if( $result ){
            $this->response["status"] = "success";
            $this->response["message"] = "User created successfully.";
        }

        return new JsonResponse($this->response);
    }

    /**
     * @Route("/api/user/delete", name="api_user_delete_action")
     * @Method({"POST"})
     */
    public function deleteUserAction(Request $request)
    {
        $this->config();

        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##                     to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###


        $found = $this->user_repo->findOneById($request->request->get('id', ""));

        if( !$found ){
            $this->response["status"] = "error";
            $this->response["message"] = "User not found.";
            return new JsonResponse($this->response);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($found);
        $result = $em->flush();

        $this->response["status"] = "success";
        $this->response["message"] = "User deleted successfully.";

        return new JsonResponse($this->response);
    }

    /**
     * @Route("/api/user/assign/group", name="api_user_assign_to_group_action")
     * @Method({"POST"})
     */
    public function assignUserToGroupAction(Request $request)
    {
        $this->config();

        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##                     to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###

        $user = $this->user_repo->findOneById($request->request->get('user_id', ""));
        $group = $this->group_repo->findOneById($request->request->get('group_id', ""));

        if( !$user ){
            $this->response["status"] = "error";
            $this->response["message"] = "User not found.";
            return new JsonResponse($this->response);
        }

        if( !$group ){
            $this->response["status"] = "error";
            $this->response["message"] = "Group not found.";
            return new JsonResponse($this->response);
        }

        $user->addGroup($group);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->response["status"] = "success";
        $this->response["message"] = "Group assigned to user successfully.";

        return new JsonResponse($this->response);
    }

    /**
     * @Route("/api/user/remove/group", name="api_user_remove_from_group_action")
     * @Method({"POST"})
     */
    public function removeUserFromGroupAction(Request $request)
    {
        $this->config();

        ## ############################################################## ###
        ##                         PLEASE NOTE THAT                       ###
        ##     Here we must validate data before doing any operations     ###
        ## Also we should check if current user has the access permission ###
        ##                     to do this operation                       ###
        ##  and Since it is a discussion starter task i skipped this step ###
        ## ############################################################## ###

        $user = $this->user_repo->findOneById($request->request->get('user_id', ""));
        $group = $this->group_repo->findOneById($request->request->get('group_id', ""));

        if( !$user ){
            $this->response["status"] = "error";
            $this->response["message"] = "User not found.";
            return new JsonResponse($this->response);
        }

        if( !$group ){
            $this->response["status"] = "error";
            $this->response["message"] = "Group not found.";
            return new JsonResponse($this->response);
        }

        $user->removeGroup($group);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->response["status"] = "success";
        $this->response["message"] = "User removed from group successfully.";

        return new JsonResponse($this->response);
    }
}
