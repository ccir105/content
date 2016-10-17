<?php 
	class test{
		public $str = "test";

		public static function getInstance()
		{
			return new Static();
		}

		public function testing()
		{
			$a = ['content'=>'new'];
			$b = ['content'=>'merge'];

			print_r(array_merge($a,$b));
		}
	}

	echo test::getInstance()->testing();