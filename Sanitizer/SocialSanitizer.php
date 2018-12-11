<?php

namespace PLejeune\UserBundle\Sanitizer;


use PLejeune\UserBundle\Entity\User;

class SocialSanitizer
{
    public static function sanitizeUser(User $user)
    {
        foreach (array("twitter", "instagram", "twitch", "facebook", "youtube") as $network) {
            if (!method_exists($user, sprintf("get%s", ucfirst($network)))) continue;
            $value = call_user_func([$user, sprintf("get%s", ucfirst($network))]);
            if (is_null($value)) continue;
            $cleaned = self::sanitizeURL($value);
            call_user_func([$user, sprintf("set%s", ucfirst($network))], $cleaned);
        }
    }

    public static function sanitizeURL($url)
    {
        if (strpos($url, "twitch")) {
            $url = str_replace("/profile", "", $url);
            $url = str_replace("/videos", "", $url);
            $step1 = implode('', array_slice(explode('/', $url), -1));
            $step2_explode = explode('?', $step1);
            return $step2_explode[0];
        }
        if (strpos($url, "youtube")) {
            $step1 = implode('/', array_slice(explode('/', $url), -2));
            return $step1;
        }
        if (strpos($url, "twitter")) {
            $step1 = implode('', array_slice(explode('/', $url), -1));
            return $step1;
        }
        if (strpos($url, "facebook")) {
            $step1 = implode('', array_slice(explode('/', $url), -1));
            return $step1;
        }
        if (strpos($url, "instagram")) {
            $step1 = implode('', array_slice(explode('/', $url), -2));
            return $step1;
        }
        return $url;
    }
}