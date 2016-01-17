<?php

namespace Solid\Nav;

use Illuminate\Support\Fluent;

class NavInstance
{

    protected $navIdPrefix = 'nav';

    /**
     * Navigation items hold by this instance
     * 
     * @var array
     */
    protected $items = [];

    /**
     * Identifier for this nav instance
     * 
     * @var string
     */
    protected $key = null;
    
    /**
     * Initialize a navigation instance
     * 
     * @param string $key Identifier for this instance
     */
    public function __construct ($key)
    {
        $this->key = $key;
    }

    /**
     * Retrieve all items as object for cleaner syntax
     * 
     * @return Object Navigation items
     */
    public function getItems()
    {
        return (object) $this->items;
    }

    /**
     * Adding navigation item node
     * @param string $id  Unique identifier for each node
     * @param array  $arr Node configuration 
     *                    e.g. [
     *                        'url' => '/some-url',
     *                        'is_active' => true,
     *                        'label' => 'Dashboard'
     *                    ]
     */
    public function add($id, $arr)
    {
        if (!$this->exists($id)) {
            $this->makeNew($id, 'node');
        }

        // Url
        if (array_key_exists('url', $arr)) {
            $this->url($arr['url'], $id);
        }

        // Label
        if (array_key_exists('label', $arr)) {
            $this->label($arr['label'], $id);
        }

        // Label
        if (array_key_exists('icon', $arr)) {
            $this->icon($arr['icon'], $id);
        }

        return $this;
    }

    /**
     * Create a new navigation section
     * This probably makes more sense if navigation is targeted for sidebar
     * 
     * @param  string $sectionName
     * @return Solid\Nav\NavInstance
     */
    public function addSection($sectionName)
    {
        if (!$this->exists($sectionName)) {
            $this->makeNew($sectionName, 'section');
        }
        return $this->section($sectionName, $sectionName);
    }

    /**
     * Assign section current navigation items
     * 
     * @param  string $sectionName
     * @param  string $id          Unique section name id
     * @return Solid\Nav\NavInstance
     */
    public function section($sectionName, $id)
    {
        if ($this->exists($id)) {
            $this->items[$id]->sectionName = $sectionName;
        }
        return $this;
    }

    /**
     * Set url for navigation item node
     * 
     * @param  string $url Url for the node
     * @param  string $id  Identifier
     * @return Solid\Nav\NavInstance
     */
    public function url($url, $id)
    {
        if ($this->exists($id)) {
            $this->items[$id]->url = $url;
        }
        return $this;
    }

    /**
     * Set label for navigation item node
     * 
     * @param  string $label Label for the node, can include HTML markup
     * @param  string $id  Node Identifier
     * @return Solid\Nav\NavInstance
     */
    public function label($label, $id)
    {
        if ($this->exists($id)) {
            $this->items[$id]->label = $label;
        }
        return $this;
    }

    /**
     * Set icon for navigation item node
     * 
     * @param  string $icon Label for the node, can include HTML markup
     * @param  string $id  Node Identifier
     * @return Solid\Nav\NavInstance
     */
    public function icon($icon, $id)
    {
        if ($this->exists($id)) {
            $this->items[$id]->icon = $icon;
        }
        return $this;
    }

    /**
     * Set navigation with identifier {$id} to active
     * 
     * @param string $id Node Identifier
     * @return Solid\Nav\NavInstance
     */
    public function setActive($id)
    {
        if ($this->exists($id)) {
            $this->items[$id]->is_active = true;
        }
        return $this;
    }

    /**
     * Check whether a node with {$id} already exists
     * 
     * @param  string $id Node Identifier
     * @return boolean
     */
    private function exists($id)
    {
        return array_key_exists($id, $this->items);
    }

    /**
     * Create a new node for this navigation
     * 
     * @param  string $id Node Identifier
     * @param  string $type Type of node
     * @return Solid\Nav\NavInstance
     */
    private function makeNew($id, $type = 'node')
    {
        $item = new Fluent;
        $item->is_active = false;
        $item->type = $type;

        $this->items[$id] = $item;
        return $this;
    }

    /**
     * For testing purpose, dump navigation
     * 
     * @return void
     */
    public function dump()
    {
        dd($this->items);
    }

}
