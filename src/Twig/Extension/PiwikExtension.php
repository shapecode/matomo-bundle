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
            'host_name'          => $host,
            'host_path'          => $trackerPath,
            'no_script_tracking' => $noScriptTracking,
            'site_id'            => $siteId,
            'paqs'               => [],
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
}
