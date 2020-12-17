<?php


class Event
{
    private  $tekst;
    private $choicesID;
    private $imagePath;

    /**
     * event constructor.
     * @param $tekst
     * @param $choicesID
     * @param $imagePath
     */
    public function __construct($tekst, $imagePath, $choicesID)
    {
        $this->tekst = $tekst;
        $this->choicesID = $choicesID;
        $this->imagePath = $imagePath;
    }

    public function getButtons()
    {
        $out = "";
        foreach($this->choicesID as $choice)
        {
            $out .= "<button onclick=\"location.href='?spot=$choice'  ;\"  type=button >$choice </button>";

        }
        return $out;
    }
    public function getHtml()
    {
//        $out = " <div>
//                 <div> <<img src=$this->imagePath>></div>
//                 <div> $this->tekst </div>
//                 <div< $this->getButtons()</div>";
        $out = "<div>  <div> <img src=\"". $this->imagePath . "\"> </div>  <div>" . $this->tekst . "</div> <div>" . $this->getButtons() . "</div></div>";
        return $out;

    }
}


