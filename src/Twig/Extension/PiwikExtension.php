<?php

namespace Shapecode\Bundle\PiwikBundle\Twig\Extension;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PiwikExtension
 *
 * @package Shapecode\Bundle\PiwikBundle\Twig\Extension
 * @author  Nikita Loges
 */
class PiwikExtension extends \Twig_Extension
{

    /** @var bool */
    protected $disabled = false;

    /** @var string */
    protected $template;

    /** @var OptionsResolver */
    protected $resolver;

    /**
     * @param $disabled
     * @param $noScriptTracking
     * @param $siteId
     * @param $host
     * @param $trackerPath
     */
    public function __construct($template, $disabled, $noScriptTracking, $siteId, $host, $trackerPath)
    {
        $this->disabled = $disabled;
        $this->template = $template;

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'host'             => $host,
            'trackerPath'      => $trackerPath,
            'noScriptTracking' => $noScriptTracking,
            'siteId'           => $siteId,
            'paq'              => [],
        ]);

        $this->resolver = $resolver;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('piwik', [$this, 'piwik'], [
                'is_safe'           => ['html'],
                'needs_environment' => true
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $env
     * @param array             $options
     *
     * @return mixed|string
     */
    public function piwik(\Twig_Environment $env, array $options = [])
    {
        if ($this->disabled) {
            return '';
        }

        $options = $this->resolver->resolve($options);

        $paq = json_encode($options['paq']);
        $host = $options['host'];
        $trackerPath = $options['trackerPath'];
        $siteId = $options['siteId'];
        $noScriptTracking = $options['noScriptTracking'];

        $host = '//' . trim($host, '/');
        $trackerPath = trim($trackerPath, '/') . '/';

        $html = $env->render($this->template, [
            'paq'              => $paq,
            'host'             => $host,
            'trackerPath'      => $trackerPath,
            'siteId'           => $siteId,
            'noScriptTracking' => $noScriptTracking,
        ]);

        return $html;
    }
}
