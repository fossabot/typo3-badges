<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony project "eliashaeussler/typo3-badges".
 *
 * Copyright (C) 2021 Elias Häußler <elias@haeussler.dev>
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

namespace App\Controller;

use App\Entity\Badge;
use App\Service\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ExtensionBadgeController.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Route(
    path: '/badge/{extension}/extension/{provider?}',
    name: 'badge.extension',
    requirements: ['extension' => '[a-z0-9_]+'],
    options: ['description' => 'Get JSON data for extension key.'],
    methods: ['GET'],
)]
final class ExtensionBadgeController extends AbstractBadgeController
{
    public function __construct(
        private ApiService $apiService,
    ) {
    }

    public function __invoke(Request $request, string $extension, string $provider = null): Response
    {
        $apiResponse = $this->apiService->getExtensionMetadata($extension);
        $extensionKey = $apiResponse[0]['key']
            ?? throw new BadRequestHttpException('Invalid API response.');

        return $this->getBadgeResponse(Badge::forExtension($extensionKey), $provider);
    }
}
