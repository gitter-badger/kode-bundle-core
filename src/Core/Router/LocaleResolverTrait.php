<?php

namespace KodeCms\KodeBundle\Core\Router;

use Symfony\Component\HttpFoundation\Request;

trait LocaleResolverTrait
{
    protected $cookieName;
    protected $hostMap;

    public function resolveLocale(Request $request, array $availableLocales)
    {
        if (!empty($this->hostMap[$request->getHost()])) {
            return $this->hostMap[$request->getHost()];
        }

        $functions = [
            'returnByQueryParameter',
            'returnByPreviousSession',
            'returnByCookie',
            'returnByLang',
        ];

        foreach ($functions as $function) {
            if ($result = $this->{$function}($request, $availableLocales)) {
                return $result;
            }
        }

        return null;
    }

    protected function returnByQueryParameter(Request $request)
    {
        // @formatter:off
        foreach (['hl', 'lang'] as $parameter) {
        // @formatter:on
            if ($request->query->has($parameter)) {
                $hostLanguage = $request->query->get($parameter);

                if (preg_match('#^[a-z]{2}(?:_[a-z]{2})?$#i', $hostLanguage)) {
                    return $hostLanguage;
                }
            }
        }

        return null;
    }

    protected function returnByPreviousSession(Request $request)
    {
        if ($request->hasPreviousSession()) {
            $session = $request->getSession();
            if ($session && $session->has('_locale')) {
                return $session->get('_locale');
            }
        }

        return null;
    }

    protected function returnByCookie(Request $request)
    {
        if ($request->cookies->has($this->cookieName)) {
            $hostLanguage = $request->cookies->get($this->cookieName);

            if (preg_match('#^[a-z]{2}(?:_[a-z]{2})?$#i', $hostLanguage)) {
                return $hostLanguage;
            }
        }

        return null;
    }

    protected function returnByLang(Request $request, array $availableLocales)
    {
        foreach ($this->parseLanguages($request) as $lang) {
            if (\in_array($lang, $availableLocales, true)) {
                return $lang;
            }
        }

        return null;
    }

    private function parseLanguages(Request $request)
    {
        $languages = [];
        foreach ($request->getLanguages() as $language) {
            if (\strlen($language) !== 2) {
                $newLang = explode('_', $language, 2);
                $languages[] = reset($newLang);
            } else {
                $languages[] = $language;
            }
        }

        return array_unique($languages) ?? [];
    }
}
