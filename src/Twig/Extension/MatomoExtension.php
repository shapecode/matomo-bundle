<?php

namespace Shapecode\Bundle\MatomoBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MatomoExtension
 *
 * @package Shapecode\Bundle\MatomoBundle\Twig\Extension
 * @author  Nikita Loges
 */
class MatomoExtension extends AbstractExtension
{

    /** @var bool */
    protected $disabled = false;

    /** @var string */
    protected $template;

    /** @var OptionsResolver */
    protected $resolver;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * @param $template
     * @param $disabled
     * @param $noScriptTracking
     * @param $siteId
     * @param $host
     * @param $trackerPath
     */
    public function __construct(RequestStack $requestStack, $template, $disabled, $noScriptTracking, $siteId, $host, $trackerPath)
    {
        $this->disabled = $disabled;
        $this->template = $template;
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('matomo', [$this, 'matomo'], [
                'is_safe'           => ['html'],
                'needs_environment' => true
            ]),
            new TwigFunction('piwik', [$this, 'matomo'], [
                'is_safe'           => ['html'],
                'needs_environment' => true
            ]),
        ];
    }

    /**
     * @param Environment $env
     * @param array       $options
     *
     * @return string
     */
    public function matomo(Environment $env, array $options = [])
    {
        if ($this->disabled) {
            return '';
        }

        $options = $this->getResolver()->resolve($options);

        $paq = $options['paqs'];
        $hostName = $options['host_name'];
        $hostPath = $options['host_path'];
        $siteId = $options['site_id'];
        $noScriptTracking = $options['no_script_tracking'];

        $hostName = '//' . trim($hostName, '/');
        $hostPath = trim($hostPath, '/') . '/';

        $html = $env->render($this->template, [
            'paqs'               => $paq,
            'host_name'          => $hostName,
            'host_path'          => $hostPath,
            'site_id'            => $siteId,
            'no_script_tracking' => $noScriptTracking,
        ]);

        return $html;
    }

    /**
     * @return OptionsResolver
     */
    protected function getResolver()
    {
        if ($this->resolver !== null) {
            return $this->resolver;
        }

        $request = $this->requestStack->getMasterRequest();

        $host = $host ?? $request->getSchemeAndHttpHost();

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'host_name'          => $host,
            'host_path'          => $trackerPath,
            'no_script_tracking' => $noScriptTracking,
            'site_id'            => $siteId,
            'paqs'               => [],
        ]);

        $this->resolver = $resolver;
    }
}
