<?php
/**
 * Created by PhpStorm.
 * User: by mengjuncheng
 * Date: 2018/9/9
 * Time: 17:17
 */

class Lib
{
    public function recursiveList(array $list,$html = "")
    {
        if(is_array($list) && isset($list['list'])){
            $html .= '<li class="dropdown">';
            if(isset($list['path'])){
                $html .= "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false' href = 'file/'".$list['path'].">".$list['title'];
            }else{
                $html .='<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">'.$list['title'];
            }
            $html .= '<span class="caret"></span></a><ul class="dropdown-menu" role="menu">';
            foreach($list['list'] as $lk=>$lv)
            {
                $html .= '<li><a  href="index.php?page='.$lv.'" >'.$lk.'</a></li>';
            }
            $html .= '</ul></li>';
        }
        return $html;
    }
}