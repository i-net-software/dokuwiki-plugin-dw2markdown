<?php

namespace dokuwiki\plugin\dw2markdown;

use dokuwiki\Menu\Item\AbstractItem;

/**
 * Class MenuItem
 *
 * Implements the Markdown export button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\dw2markdown
 */
class MenuItem extends AbstractItem
{

    /** @var string do action for this plugin */
    protected $type = 'export_dw2markdown';

    /** @var string icon file */
    protected $svg = __DIR__ . '/file-markdown.svg';

    /**
     * Get label from plugin language file
     *
     * @return string
     */
    public function getLabel()
    {
        $hlp = plugin_load('action', 'dw2markdown');
        return $hlp->getLang('export_markdown_button');
    }
}
