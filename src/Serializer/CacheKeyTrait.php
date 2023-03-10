<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Core\Serializer;

trait CacheKeyTrait
{
    /**
     * @return string|bool
     */
    private function getCacheKey(?string $format, array $context)
    {
        foreach ($context[self::EXCLUDE_FROM_CACHE_KEY] ?? $this->defaultContext[self::EXCLUDE_FROM_CACHE_KEY] as $key) {
            unset($context[$key]);
        }
        unset($context[self::EXCLUDE_FROM_CACHE_KEY]);
        unset($context[self::OBJECT_TO_POPULATE]);
        unset($context['cache_key']); // avoid artificially different keys

        try {
            return hash('xxh128', $format.serialize([
                    'context' => $context,
                    'ignored' => $context[self::IGNORED_ATTRIBUTES] ?? $this->defaultContext[self::IGNORED_ATTRIBUTES],
                ]));
        } catch (\Exception) {
            // The context cannot be serialized, skip the cache
            return false;
        }
    }
}
