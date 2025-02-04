<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony project "eliashaeussler/typo3-badges".
 *
 * Copyright (C) 2022 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * ApiSpecificationControllerTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class ApiSpecificationControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function controllerReturnsApiSpecificationAsJson(): void
    {
        $client = self::createClient();
        $client->request('GET', '/spec');
        $json = $client->getResponse()->getContent();
        $expected = $this->getExpectedApiSpecification();

        if (false === $json) {
            throw new \RuntimeException('Invalid JSON data.');
        }

        self::assertResponseIsSuccessful();
        self::assertJson($json);
        self::assertSame($expected, \json_decode($json, true, \JSON_THROW_ON_ERROR));
    }

    /**
     * @return array<string, mixed>
     */
    private function getExpectedApiSpecification(): array
    {
        $apiSpecification = Yaml::parseFile(dirname(__DIR__, 2).'/spec/typo3-badges.oas3.yaml');

        self::assertIsArray($apiSpecification);

        return $apiSpecification;
    }
}
