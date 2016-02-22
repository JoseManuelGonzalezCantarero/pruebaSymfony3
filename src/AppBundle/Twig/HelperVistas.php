<?php

namespace AppBundle\Twig;


class HelperVistas extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'generateTable' => new \Twig_Function_Method($this, 'generateTable')
        );
    }

    public function generateTable($resultSet)
    {
        $table = "<table class='table' border=1";
        for ($i = 0; $i < count($resultSet); $i++)
        {
            $table.="<tr>";
            for ($j = 0; $j < count($resultSet[$i]); $j++)
            {
                $resultSet_values = array_values($resultSet[$i]);
                $table.="<td>".$resultSet_values[$j]."</td>";
            }
            $table.="</tr>";
        }
        $table.="</table";
        return $table;
    }

    public function getName()
    {
        return "app_bundle";
    }

}