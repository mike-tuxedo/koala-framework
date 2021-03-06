<?php
abstract class Kwf_Update_Action_Rrd_AbstractTest extends Kwf_Test_TestCase
{
    public function setUp()
    {
        Kwf_Component_Data_Root::setComponentClass(false);
        if (!`which rrdtool`) {
            $this->markTestSkipped();
        }

    }

    public function tearDown()
    {
    }

    protected function _systemCheckRet($cmd)
    {
        $ret = null;
        system($cmd, $ret);
        if ($ret != 0) throw new Kwf_ClientException("Command failed");
    }

    protected function _createTestFile()
    {
        $file = tempnam('/tmp', 'rrdtest');

        $interval = 60;
        $testSteps = 3;
        $cmd = "rrdtool create $file ";
        $cmd .= "--start ".(time()-($testSteps*60)-1)." ";
        $cmd .= "--step ".($interval)." ";
        $cmd .= "DS:testx:ABSOLUTE:".($interval*2).":0:1000 ";
        $cmd .= "DS:testxx:COUNTER:".($interval*2).":0:".(2^64)." ";
        $cmd .= "RRA:AVERAGE:0.6:1:2016 "; //1 woche
        $this->_systemCheckRet($cmd);

        for ($i = 0; $i < $testSteps; $i++) {
            $t = time()-(($testSteps-$i)*60);
            $cmd = "rrdtool update $file $t:".rand(0, 1000).':'.rand(0,10000);
            $this->_systemCheckRet($cmd);
        }
        return $file;
    }
}
