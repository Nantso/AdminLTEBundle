<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminLTEBundle\Controller;

use AdminLTEBundle\Event\ShowUserEvent;
use AdminLTEBundle\Event\SidebarMenuEvent;
use AdminLTEBundle\Event\ThemeEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends Controller
{
    /**
     * Block used in macro avanzu_sidebar_user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPanelAction()
    {
        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_SIDEBAR_USER)) {
            return new Response();
        }

        /** @var ShowUserEvent $userEvent */
        $userEvent = $this->getDispatcher()->dispatch(ThemeEvents::THEME_SIDEBAR_USER, new ShowUserEvent());

        return $this->render(
            '@AvanzuAdminTheme/Sidebar/user-panel.html.twig',
                [
                    'user' => $userEvent->getUser(),
                ]
        );
    }

    /**
     * @return EventDispatcher
     */
    protected function getDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Block used in macro avanzu_sidebar_search
     *
     * @return Response
     */
    public function searchFormAction()
    {
        return $this->render('@AvanzuAdminTheme/Sidebar/search-form.html.twig', []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function menuAction(Request $request)
    {
        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_SIDEBAR_SETUP_MENU)) {
            return new Response();
        }

        /** @var SidebarMenuEvent $userEvent */
        $event = $this->getDispatcher()->dispatch(ThemeEvents::THEME_SIDEBAR_SETUP_MENU, new SidebarMenuEvent($request));

        return $this->render(
            '@AvanzuAdminTheme/Sidebar/menu.html.twig',
                [
                    'menu' => $event->getItems(),
                ]
        );
    }
}