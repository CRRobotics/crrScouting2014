<?php
class Alliance{
	const MISS_PENALTY=10;//how much time is deducted from a missed shot
	
	private $robotA=new Robot();
	private $robotB=new Robot();
	private $robotC=new Robot();
	
	private $ballOwner=$robotA;
	private $ballHeat=0;
	private $score=0;
	private $passedOver=false;
	
	private $robotTeam = array($robotA,$robotB,$robotC);
	
	public function __construct(Robot $a, Robot $b, Robot $c){
		this->$robotA=$a;
		this->$robotB=$b;
		this->$robotC=$c;
		$a->reset();
		$b->reset();
		$c->reset();
	}
	
	//passes the ball to a random robot params: 
	//$not: what robot just passed the ball and therefore can't recieve
	/*-IMPORTANT- This method doesn't do anything to remove the ball from
	one robot, make sure that a robot has already passed a pass check BEFORE
	calling this method
	*/
	public function pass($not){//TODO:add some protection for bad calls
		if($not==$robotA)
			$ballOwner=(rand(0,1)<0.5 ? $robotB:$robotC);
		else if($not==$robotB)
			$ballOwner=(rand(0,1)<0.5 ? $robotA:$robotC);
		else if($not==$robotC)
			$ballOwner=(rand(0,1)<0.5 ? $robotA:$robotB);
	}
	
	//If it hasn't already been done and the robots are capable pass the ball over the truss 
	//$robot1 should be the passing robot and $robot2 the recieving
	public function passOverBar($robot1,$robot2){
		if(!$passedOver && $robot1->getPassOver() && $robot2->getCatchOver()){
			$passedOver=True;
			$ballHeat+=10;
			if(rand(0,1)<$robot2->getCatchProb())
				$ballHeat+=10;
		} else {
			pass($robot1);
		}
	}
	
	//calls robot->tick() methods and does the other things in this decade
	//defended should be a reference
	public function tick($defended){
		$d=0;
		if($robotA->tick()){
			$task=$robotA->getTask()
			if(strcmp($task,"pass")==0){
				passOverBar($robotA,$robotB);//TODO?:make this randomized
			}else if(strcmp($task,"shoot")==0){
				score+=($robotA->getShoot()>=2 ? 10:1)+$ballHeat;
			}else if(strcmp($task,"defend")==0){
				$d+=$robotA->getDefended();
			}
		}
		if($robotB->tick()){
			$task=$robotB->getTask()
			if(strcmp($task,"pass")==0){
				passOverBar($robotB,$robotC);//TODO?:make this randomized
			}else if(strcmp($task,"shoot")==0){
				score+=($robotB->getShoot()>=2 ? 10:1)+$ballHeat;
			}else if(strcmp($task,"defend")==0){
				$d+=$robotA->getDefended();
			}
		}
		if($robotC->tick()){
			$task=$robotC->getTask()
			if(strcmp($task,"pass")==0){
				passOverBar($robotC,$robotA);//TODO?:make this randomized
			}else if(strcmp($task,"shoot")==0){
				score+=($robotC->getShoot()>=2 ? 10:1)+$ballHeat;
			}else if(strcmp($task,"defend")==0){
				$d+=$robotA->getDefended();
			}
		}
		$defended=$d;
	}
	
	//Getter methods
	public function getRobots()
	{
		return $robotTeam;
	}
	
	public function getBallHeat(){//returns how many points a ball will yield if scored
		return $ballHeat;
	}
	
	public function getPassedOver(){//return whether the ball has been passed over the bar
		return $passedOver;
	}
}
//we choose to go to the moon
?>
