<?php
/**
 * DokuWiki Plugin dw2markdown (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  i-net software <tools@inetsoftware.de>
 */

class action_plugin_dw2markdown extends DokuWiki_Action_Plugin
{
    /**
     * Register the events
     *
     * @param Doku_Event_Handler $controller
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addSvgButton', array());
    }


    /**
     * Add 'Export to Markdown' button to page tools, SVG based
     *
     * @param Doku_Event $event
     */
    public function addSvgButton(Doku_Event $event)
    {
        global $INFO;

        if ($event->data['view'] != 'page' || !$this->getConf('showexportbutton')) {
            return;
        }

        if (! $INFO['exists']) {
            return;
        }

        array_splice($event->data['items'], -1, 0, [new \dokuwiki\plugin\dw2markdown\MenuItem()]);
    }
}
