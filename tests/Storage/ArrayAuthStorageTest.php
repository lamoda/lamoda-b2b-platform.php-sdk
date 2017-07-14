<?php
declare(strict_types=1);

namespace Tests\LamodaB2B\Storage;

use LamodaB2B\Exception\AuthConfigurationException;
use LamodaB2B\Model\Auth;
use LamodaB2B\Storage\ArrayAuthStorage;
use PHPUnit\Framework\TestCase;

class ArrayAuthStorageTest extends TestCase
{
    /**
     * @param array $storageData
     * @param string $identity
     * @param Auth $expectedAuth
     *
     * @dataProvider dataGetWithCorrectData
     */
    public function testGetWithCorrectData(array $storageData, string $identity, Auth $expectedAuth)
    {
        $storage = new ArrayAuthStorage($storageData);
        $actualAuth = $storage->get($identity);
        $this->assertEquals($expectedAuth, $actualAuth);
    }

    public function dataGetWithCorrectData()
    {
        $identity = 'test';
        $storageData = [
            'test' => [
                'client_id' => 'test_client_id',
                'client_secret' => 'test_client_secret',
            ]
        ];
        return [
            [
                $storageData,
                $identity,
                new Auth('test_client_id', 'test_client_secret')
            ]
        ];
    }

    /**
     * @param array $storageData
     * @param string $identity
     *
     * @dataProvider dataWillThrowExceptionWithInvalidData
     */
    public function testWillThrowExceptionWithInvalidData(array $storageData, string $identity)
    {
        $storage = new ArrayAuthStorage($storageData);
        $this->expectException(AuthConfigurationException::class);
        $storage->get($identity);
    }

    public function dataWillThrowExceptionWithInvalidData()
    {
        return [
            [
                [
                    'existedKey' => [
                        'unexpected_key' => 'value',
                        'client_secret' => 'test_client_secret',
                    ]
                ],
                'existedKey'
            ],
            [
                [
                    'existedKey' => [
                        'client_id' => 'test_client_id',
                        'unexpected_key' => 'value',
                    ]
                ],
                'existedKey'
            ],
            [
                [
                    'existedKey' => [
                        'client_id' => 'test_client_id',
                        'client_secret' => 'test_client_secret',
                    ]
                ],
                'nonexistentKey'
            ]
        ];
    }
}