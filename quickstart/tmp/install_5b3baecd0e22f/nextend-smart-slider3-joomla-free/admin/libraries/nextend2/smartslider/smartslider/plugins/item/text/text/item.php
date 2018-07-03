<?php

N2Loader::import('libraries.renderable.layers.itemFactory', 'smartslider');

class N2SSItemText extends N2SSItemAbstract {

    protected $type = 'text';

    public function render() {
        return $this->getHtml();
    }

    public function _renderAdmin() {
        return $this->getHtml();
    }

    private function getHTML() {
        $owner = $this->layer->getOwner();

        $font = $owner->addFont($this->data->get('font'), 'paragraph', 'div#' . $owner->getElementID() . ' .n2-ss-layer ');

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        $html          = '';
        $content       = str_replace('<p>', '<p class="' . $font . ' ' . $style . ' n2-ow">', $this->wpautop($this->closeTags($owner->fill($this->data->get('content', '')))));
        $contentTablet = str_replace('<p>', '<p class="' . $font . ' ' . $style . ' n2-ow">', $this->wpautop($this->closeTags($owner->fill($this->data->get('contenttablet', '')))));
        $contentMobile = str_replace('<p>', '<p class="' . $font . ' ' . $style . ' n2-ow">', $this->wpautop($this->closeTags($owner->fill($this->data->get('contentmobile', '')))));
        $class         = '';

        if ($contentMobile == '') {
            $class .= ' n2-ss-mobile';
        } else {
            $html .= N2Html::tag('div', array(
                'class' => 'n2-ss-mobile n2-ow n2-ow-all'
            ), $contentMobile);
        }

        if ($contentTablet == '') {
            $class .= ' n2-ss-tablet';
        } else {
            $html .= N2Html::tag('div', array(
                'class' => 'n2-ss-tablet n2-ow n2-ow-all' . $class
            ), $contentTablet);
            $class = '';
        }

        $html .= N2Html::tag('div', array(
            'class' => 'n2-ow n2-ow-all n2-ss-desktop' . $class
        ), $content);

        return $html;
    }


    public function closeTags($html) {
        $html = str_replace(array(
            '<>',
            '</>'
        ), array(
            '',
            ''
        ), $html);
        // Put all opened tags into an array
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];   #put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # Check if all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                if ($openedtags[$i] != 'br') {
                    // Ignores <br> tags to avoid unnessary spacing
                    // at the end of the string
                    $html .= '</' . $openedtags[$i] . '>';
                }
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    private function wpautop($pee, $br = true) {
        $pre_tags = array();

        if (trim($pee) === '') return '';

        $pee = $pee . "\n"; // just to make things a little easier, pad the end

        if (strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee  = array_pop($pee_parts);
            $pee       = '';
            $i         = 0;

            foreach ($pee_parts as $pee_part) {
                $start = strpos($pee_part, '<pre');

                // Malformed html?
                if ($start === false) {
                    $pee .= $pee_part;
                    continue;
                }

                $name            = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

                $pee .= substr($pee_part, 0, $start) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }

        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|noscript|legend|section|article|aside|hgroup|header|footer|nav|figure|details|menu|summary)';
        $pee       = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
        $pee       = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee       = str_replace(array(
            "\r\n",
            "\r"
        ), "\n", $pee); // cross-platform newlines

        if (strpos($pee, '</object>') !== false) {
            // no P/BR around param and embed
            $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
            $pee = preg_replace('|\s*</object>|', '</object>', $pee);
            $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
        }

        if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
            // no P/BR around source and track
            $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
            $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
            $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
        }

        $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
        // make paragraphs, including one at the end
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee  = '';

        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        if ($br) {
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', array(
                $this,
                '_autop_newline_preservation_helper'
            ), $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }

        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);

        if (!empty($pre_tags)) $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

        return $pee;
    }

    public function _autop_newline_preservation_helper($matches) {
        return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
    }
}