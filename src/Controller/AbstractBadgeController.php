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

namespace App\Controller;

use App\Badge\Provider\BadgeProviderFactory;
use App\Entity\Badge;
use App\Exception\InvalidProviderException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * AbstractBadgeController.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
abstract class AbstractBadgeController
{
    protected BadgeProviderFactory $badgeProviderFactory;

    /**
     * @required
     */
    public function setBadgeProviderFactory(BadgeProviderFactory $badgeProviderFactory): void
    {
        $this->badgeProviderFactory = $badgeProviderFactory;
    }

    protected function getBadgeResponse(Badge $badge, string $provider = null): Response
    {
        try {
            $providerClass = $this->badgeProviderFactory->get($provider);
        } catch (InvalidProviderException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $providerClass->createResponse($badge);
    }
}
