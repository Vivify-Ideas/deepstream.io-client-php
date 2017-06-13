<?php
use PHPUnit\Framework\TestCase;

define( 'API_URL', 'http://localhost:8000/api/v1' );

final class ClientTest extends TestCase
{
//    public function testPresence()
//    {
//        $client = new DeepstreamClient( API_URL );
//        $result = $client->getPresence();
//        var_dump($result);
//        $this->assertEquals( $result, false );
//    }
    
    public function testRecordDoesNotExist()
    {
        $client = new DeepstreamClient( API_URL );
        $result = $client->getRecord( 'user/wolfram' );
        $this->assertEquals( $result, false );
    }
    
    public function testWritesFullRecord()
    {
        $client = new DeepstreamClient( API_URL );
        $result = $client->setRecord( 'user/wolfram', array( 'lastname'=>'Hempel') );
        $this->assertEquals( $result, true );
    }
    
    public function testReadsRecord()
    {
        $client = new DeepstreamClient( API_URL );
        $result = $client->getRecord( 'user/wolfram' );
        $this->assertEquals( $result->lastname, 'Hempel' );
    }
    
    public function testGetsRecordVersion()
    {
        $client = new DeepstreamClient( API_URL );
        $result = $client->getRecordVersion( 'user/wolfram' );
        $this->assertEquals( $result, 1 );
    }
    
    public function testWritesRecordPath()
    {
        $client = new DeepstreamClient( API_URL );
        $setResult = $client->setRecord( 'user/wolfram', 'age', 32 );
        $this->assertEquals( $setResult, true );
        $getResult = $client->getRecord('user/wolfram');
        $this->assertEquals( $getResult->lastname, 'Hempel');
        $this->assertEquals( $getResult->age, 32 );
    }
    
    public function testGetsRecordVersionAfterPatch()
    {
        $client = new DeepstreamClient( API_URL );
        $result = $client->getRecordVersion( 'user/wolfram' );
        $this->assertEquals( $result, 2 );
    }
    
    public function testDeletesRecord()
    {
        $client = new DeepstreamClient( API_URL );
        $resultDelete = $client->deleteRecord( 'user/wolfram' );
        $this->assertEquals( $resultDelete, true );
        $resultGet = $client->getRecord( 'user/wolfram' );
        $this->assertEquals( $resultGet, false );
    }
    
    public function testMakesRpc()
    {
        $client = new DeepstreamClient( API_URL );
        $response = $client->makeRpc( 'times-two', 7 );
        $this->assertEquals( $response, 14 );
    }
    
    public function testResetsTestProvider()
    {
        $client = new DeepstreamClient( API_URL );
        $response = $client->makeRpc( 'reset-test-provider' );
        $this->assertEquals( $response, 'OK' );
    }
    
    public function testGetsEmptyEventData()
    {
        $client = new DeepstreamClient( API_URL );
        $response = $client->makeRpc( 'get-event-info' );
        $this->assertEquals( count( $response ), 0 );
    }
    
    public function testEmitsEvent()
    {
        $client = new DeepstreamClient( API_URL );
        $responseEmit = $client->emitEvent( 'test-event', 'some-data' );
        $this->assertEquals( $responseEmit, true );
        $response = $client->makeRpc( 'get-event-info' );
        $this->assertEquals( count( $response ), 1 );
        $this->assertEquals( $response[ 0 ]->name, 'test-event' );
        $this->assertEquals( $response[ 0 ]->data, 'some-data' );
    }
}