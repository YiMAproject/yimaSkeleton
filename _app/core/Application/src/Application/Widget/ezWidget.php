<?php
namespace Application\Widget;

use yimaWidgetator\Widget\AbstractWidget;

class ezWidget extends AbstractWidget
{
    protected $name;

    /**
     * Render widget as string output
     *
     * @return string
     */
    public function render()
    {
        return '<p>Hi, '.$this->getName().' this is widget</p>';
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}