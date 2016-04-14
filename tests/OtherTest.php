<?php
require_once 'r.php';


class OtherTests extends PHPUnit_Framework_TestCase
{
    private static $value = null;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_tail() {
        $this->assertEquals(R::tail([1,2,3]), [2,3]);
        $this->assertEquals(R::tail([1,2]), [2]);
        $this->assertEquals(R::tail([1]), []);
        $this->assertEquals(R::tail([]), []);

        $this->assertEquals(R::tail('abc'), 'bc');
        $this->assertEquals(R::tail('ab'), 'b');
        $this->assertEquals(R::tail('a'), '');
        $this->assertEquals(R::tail(''), '');
    }

    public function test_tap() {
        $m = 0;
        $setX = function($x) use($m){
            self::$value = $x;
        };
        $this->assertEquals((R::$tap)($setX, 100), 100);
        $this->assertEquals(self::$value, 100);
    }

    public function test_identity() {
        $this->assertEquals((R::$times)(R::$identity, 5), [0,1,2,3,4]);
    }

    public function test_pipe() {
        $f = R::pipe(R::$negate, R::$inc);
        $this->assertEquals($f(3), -2);
    }

    public function test_useWith() {
        $this->assertEquals((R::$useWith)(R::$multiply, [R::$identity, R::$identity])(3, 4), 12);
        $this->assertEquals((R::$useWith)(R::$multiply, [R::$identity, R::$identity])(3)(4), 12);
        $this->assertEquals((R::$useWith)(R::$multiply, [R::$dec, R::$inc])(3, 4), 10);
        $this->assertEquals((R::$useWith)(R::$multiply, [R::$dec, R::$inc])(3)(4), 10);
    }

    public function test_projection() {
        $abby = ["name"=> 'Abby', "age"=> 7, "hair"=> 'blond', "grade"=> 2];
        $fred = ["name"=> 'Fred', "age"=> 12, "hair"=> 'brown', "grade"=> 7];
        $kids = [$abby, $fred];
        $this->assertEquals((R::$project)(['name', 'grade'], $kids), [["name"=> 'Abby', "grade"=> 2], ["name"=> 'Fred', "grade"=> 7]]);

    }

    public function test_mapObjIndexed() {
        $values = ["x" => 1, "y" => 2, "z" => 3];
        $prependKeyAndDouble = function($num, $key, $obj) {return $key . ($num*2);};
        $this->assertEquals((R::$mapObjIndexed)($prependKeyAndDouble, $values),
            ["x" => 'x2', "y" => 'y4', "z" => 'z6']);
    }

    // public function test_compose() {
    //     $f = (R::$compose)(R::$inc, R::$negate);
    //     $this->assertEquals($f(3), -2);        
    // }
/*
    public function test_chain() {
        $duplicate = function($n) {
            return [$n, $n];
        };

        $this->assertEquals((R::$chain)($duplicate, [1,2,3]), [1,1,2,2,3,3]);
    }
    */
}
