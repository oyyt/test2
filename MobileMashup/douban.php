<?php
/*author:   OuyangYutong
  function: Obtain the info from douban
*/

class douban{

	function getMovieInfo($inputValues,$outputNames)
	{
		echo douabn;
		var_dump( $inputValues);
		$testResult['name'] = $inputValues[0];
		return $testResult;
	}
	
  function array_remove(&$arr,$offset){  
       array_splice($arr,$offset,1);  
    }  

	
	//��������
function search_link($moviename)
{
		
     //����url������max-results�ɸ�����Ҫ����
        $urlString = 'http://api.douban.com/movie/subjects?q='.$moviename.'&start-index=1&max-results=1&alt=json';
	      $urlString=mb_convert_encoding($urlString, "UTF-8", "GBK");//��Urlת��Ϊutf-8����
        $r = new HttpRequest($urlString,HttpRequest::METH_GET);//����
        $response = $r->send();
        $result = $r->getResponseBody();
        $obj = json_decode($result);//������json��ʽ

		if($entry = @$obj->{'entry'}){
				//�������Ӳ����������з���
				for($i = 0;$i<sizeof($entry);$i++){
						$link=$entry[$i]->{'link'}; 
						for($j = 0;$j<sizeof($link);$j++){
							  $arr = (array)$link[$j];
							  $key = array_search("self",$arr);
							 // echo $key;
							  if($key){									//�ж�key�Ƿ����
								  $str = $arr["@href"].'?alt=json'; //���췵�����ӵĸ�ʽ
								  break;
						
							  }	
						}
						$link_array[] = $str;
				}
				return $link_array;//��������
		} else
				echo"Douban failed!";
				}
				
		//��ѯ���Ӳ�������������$GLOBAL ����
function get_info($urlString)
{
        $r = new HttpRequest($urlString,HttpRequest::METH_GET);
        $response = $r->send();
        $result = $r->getResponseBody();
		
		//if..else.. �жϴ������Ƿ�Ϊ��
        if ($obj = json_decode($result)){
        	// var_dump($obj);
		    //��ӰƬ����Ϣ����ȫ��������
			$title_temp = $obj->{'title'};
			$title = $title_temp->{'$t'};
			$author_temp = $obj->{'author'};
			foreach ($author_temp as $a)
				foreach ($a as $b)
				   $author[] = $b->{'$t'};

			$summary_temp = $obj->{'summary'};

			$summary = $summary_temp->{'$t'};
			echo $summary;

			$ID_temp = $obj->{'id'};
			$ID_temp = $ID_temp->{'$t'};
		 	$ID = explode("/", $ID_temp); 
		 	$ID = $ID[sizeof($ID)-1];
			
			$link_temp = $obj->{'link'};
			foreach ($link_temp as $a){
				foreach ($a as $b){
					$link[] = $b;
				}
			}
			
			$gd = (array)$obj->{'gd:rating'};	
			$gd['numRaters'] = $gd['@numRaters'];
			$gd['average'] = $gd['@average'];
			$gd['max'] = $gd['@max'];
			$gd['min'] = $gd['@min'];
		 
			
			$db_array = array();
			$db = $obj->{'db:attribute'};
			foreach ($db as $value){
				$value_array = (array)($value);
				$v = $value_array["@name"];
				$k = $value_array["\$t"];
				if (array_key_exists("@lang",$value_array)){
				    $lang=$value_array["@lang"];
					$k=$k.'['.$lang.']';
				}		
			    $db_array[$v][]=$k; 
			}
			return $title;
			
			//�趨��Ӧ����ļ�ֵ
			@$db_array_key = array('[name]','[alias]','[director]','[scenario]','[tomlive]','[IMDB link]','[Product]',
			                   '[country]','[type]','[release date]','[Show length]','[Set Several]','[language]','[actor]');
			@$db_array_value = array($db_array["title"],$db_array["aka"],$db_array["director"],$db_array["writer"],$db_array["website"],
			                     $db_array["imdb"],$db_array["year"],$db_array["country"],$db_array["movie_type"],$db_array["pubdate"],
								 $db_array["movie_duration"],$db_array["episodes"],$db_array["language"],$db_array["cast"]);
								 
			
			@$db_array = array_combine($db_array_key,$db_array_value);
	    }   
	    else{
		        echo "Empty link!";
		}
		$info=array("title"=>$title,"author"=>$author,"summary"=>$summary,"ID"=>$ID,"link"=>$link,"gd"=>$gd,"db_array"=>$db_array);
	    
	
		}
	
}



   


	