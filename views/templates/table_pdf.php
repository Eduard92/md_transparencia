<page backtop="30mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<page_header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;text-align:left;">
                    {{ asset:image file="pdf/cintillo_header.png" style="width:100%;" }}
                    
                </td>
                
                
            </tr>
            
        </table>
    </page_header>
    <br />
    <table style="font-size: 10px;">
        <tr>
            <?php foreach($campos as $campo):?>
            <th style="background: #8A8D8E; color:#FFF; padding:3px;" width="<?=(900/count($campos))?>"><?=$campo->nombre?></th>
            <?php endforeach;?>
        </tr>
        <?php foreach($rows as $row):?>
            <?php $values = json_decode($row->campos)?>
            <tr>
                <?php foreach($campos as $campo):?>
                 <?php 
                                             $html = '';
                                             
                                             switch($campo->tipo){
                                                case 'upload':
                                                    
                                                    $file   = Files::get_file($values->{$campo->slug});
                                                    //print_r($file);
                                                    $html   = '<a  href="'.$file['data']->path.'" target="_blank">'.$file['data']->path.'</a>'; 
                                                break; 
                                                case 'input':
                                                    $html = $values->{$campo->slug};
                                                break;
                                                default:
                                                break;
                                             }
                 ?>
                <td style="text-align: center;" width="<?=(900/count($campos))?>"><?=$html?></td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
    </table>
 </page>