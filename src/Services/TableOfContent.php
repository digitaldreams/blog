<?php

namespace Blog\Services;

use Illuminate\Support\Str;

class TableOfContent
{
    public $parents = [];

    public $topLevelHeading = 6;

    public $headings = [];

    protected $html;

    public static $headingNumber = [
        'h1' => 1,
        'h2' => 2,
        'h3' => 3,
        'h4' => 4,
        'h5' => 5,
        'h6' => 6,
    ];
    /**
     * @var \DOMDocument
     */
    protected $dom;

    public function __construct($html)
    {
        $html = (string) trim(str_replace(['<body>', '</body>'], '', (string) $html->body()));
        $this->html = $html;
        $this->dom = @new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $this->dom->loadHTML($html);
        libxml_clear_errors();
    }

    /**
     * @return $this
     */
    public function headings()
    {
        $ret = [];
        $list = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        foreach ($list as $key => $level) {
            $headings = $this->heading($level);
            $current = $key + 1;
            if (!empty($headings) && $this->topLevelHeading > $current) {
                $this->topLevelHeading = $current;
            }
        }

        return $this;
    }

    /**
     * @param $level
     *
     * @return array
     */
    protected function heading($level)
    {
        $ret = [];
        $headings = $this->dom->getElementsByTagName($level);
        foreach ($headings as $heading) {
            $ret[] = $heading->nodeValue;

            if (empty($heading->nodeValue)) {
                continue;
            }
            $this->headings[$heading->getLineNo()] = [
                'name' => $heading->nodeName,
                'value' => $heading->nodeValue,
                'html' => $heading->firstChild->ownerDocument->saveXML($heading),
            ];
        }

        return $ret;
    }

    protected function parseParent()
    {
        ksort($this->headings);
        $parent = 'h' . $this->topLevelHeading;
        foreach ($this->headings as $line => $heading) {
            if ($heading['name'] == $parent) {
                $this->parents[$line] = $heading;
            }
        }
        ksort($this->parents);

        return $this;
    }

    public function putChild()
    {
        ksort($this->headings);
        $parentLines = array_keys($this->parents);
        ksort($parentLines);
        foreach ($this->headings as $line => $heading) {
            if (!isset($this->parents[$line])) {
                foreach ($parentLines as $key => $pline) {
                    if ($line > $pline) {
                        $nextPline = isset($parentLines[$key + 1]) ? $parentLines[$key + 1] : 10000000;
                        if ($line < $nextPline) {
                            $this->parents[$pline]['childs'][$line] = $heading;
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function parentChildSort()
    {
        foreach ($this->parents as $line => $parent) {
            if (isset($parent['childs']) && !empty($parent['childs'])) {
                $parentHeading = 6;
                $childs = $parent['childs'];
                $headingTags = array_column($childs, 'name');
                foreach ($headingTags as $headingTag) {
                    $hNumber = static::$headingNumber[$headingTag] ?? 6;
                    if ($hNumber < $parentHeading) {
                        $parentHeading = $hNumber;
                    }
                }
                $this->parents[$line]['childs'] = $this->tbc('h' . $parentHeading, $childs);
            }
        }

        return $this->parents;
    }

    public function tbc($parentHeading, $nodes)
    {
        $parents = [];
        foreach ($nodes as $line => $heading) {
            if ($heading['name'] == $parentHeading) {
                $parents[$line] = $heading;
            }
        }
        $parentLines = array_keys($parents);
        foreach ($nodes as $line => $node) {
            if (!isset($parents[$line])) {
                foreach ($parentLines as $key => $pline) {
                    if ($line > $pline) {
                        $nextPline = isset($parentLines[$key + 1]) ? $parentLines[$key + 1] : 10000000;
                        if ($line < $nextPline) {
                            $parents[$pline]['childs'][$line] = $node;
                        }
                    }
                }
            }
        }

        return $parents;
    }

    public function process()
    {
        $parents = $this->headings()->parseParent()->putChild()->parentChildSort();

        return $this->listItem();
    }

    public function listItem()
    {
        $html = '<ul>';
        $this->parents;
        foreach ($this->parents as $parent) {
            $html .= '<li><a href="#' . Str::slug($parent['value']) . '">' . Str::limit($parent['value'], 60) . '</a>';
            if (isset($parent['childs']) && !empty($parent['childs'])) {
                $html .= '<ul>';
                foreach ($parent['childs'] as $pchild) {
                    $html .= '<li><a href="#' . Str::slug($pchild['value']) . '">' . Str::limit($pchild['value'], 40) . '</a>';
                    if (isset($pchild['childs']) && !empty($pchild['childs'])) {
                        $html .= '<ul>';
                        foreach ($pchild['childs'] as $ppchild) {
                            $html .= '<li><a href="#' . Str::slug($ppchild['value']) . '">' . Str::limit($ppchild['value'], 40) . '</a>';
                        }
                        $html .= '</ul>';
                    }
                }
                $html .= '</ul>';
            }

            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * @return string
     */
    public function anchorLinkToHeadings()
    {
        $html = $this->html;
        $replaceArr = [];
        foreach ($this->headings as $line => $heading) {
            $start = '<' . $heading['name'] . '>';
            $end = '</' . $heading['name'] . '>';
            $anchor = '<a name="' . Str::slug($heading['value']) . '">' . $heading['value'] . '</a>';
            $replaceHtml = $start . $anchor . $end;
            $replaceArr[$heading['html']] = $replaceHtml;
        }

        return strtr($html, $replaceArr);
    }
}
