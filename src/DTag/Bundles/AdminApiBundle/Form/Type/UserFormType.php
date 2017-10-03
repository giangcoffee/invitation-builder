<?php

namespace DTag\Bundles\AdminApiBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DTag\Bundles\UserBundle\Entity\User;
use DTag\Form\Type\AbstractRoleSpecificFormType;
use DTag\Model\User\Role\AdminInterface;
use DTag\Model\User\Role\BrokerInterface;
use DTag\Model\User\UserEntityInterface;

class UserFormType extends AbstractRoleSpecificFormType
{
    static $REPORT_SETTINGS_ADTAG_KEY_VALUES = [
        'totalOpportunities',
        'firstOpportunities',
        'impressions',
        'verifiedImpressions',
        'unverifiedImpressions',
        'blankImpressions',
        'voidImpressions',
        'clicks',
        'passbacks',
        'fillRate',
    ];
    const MODULE_CONFIG = 'moduleConfigs';
    const VIDEO_MODULE = 'MODULE_VIDEO_ANALYTICS';
    const VIDEO_PLAYERS = 'players';
    protected $listPlayers = ['5min', 'defy', 'jwplayer5', 'jwplayer6', 'limelight', 'ooyala', 'scripps', 'ulive'];

//    private $userRole;

    private $oldSettings;

    public function __construct(UserEntityInterface $userRole)
    {
        $this->setUserRole($userRole);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('plainPassword')
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('email')
            ->add('phone')
            ->add('city')
            ->add('state')
            ->add('address')
            ->add('postalCode')
            ->add('country')
            ->add('settings')
            ->add('moduleConfigs')
        ;

        if($this->userRole instanceof AdminInterface){
            $builder
                ->add('enabled')
                ->add('enabledModules', 'choice', [
                    'mapped' => false,
                    'empty_data' => null,
                    'multiple' => true,
                    'choices' => [
                        'MODULE_DISPLAY'         => 'Display',
                        'MODULE_VIDEO_ANALYTICS'           => 'Video',
                        'MODULE_ANALYTICS'       => 'Analytics',
                        'MODULE_FRAUD_DETECTION' => 'Fraud Detection'
                    ],
                ])
                ->add('billingRate')

                ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var UserEntityInterface $user */
                    $user = $event->getData();
                    $form = $event->getForm();

                    $modules = $form->get('enabledModules')->getData();
                    $moduleConfigs = $form->get(self::MODULE_CONFIG)->getData();

                    if (null !== $modules && is_array($modules)) {
                        $user->setEnabledModules($modules);
                    }

                    if(!is_array($moduleConfigs)) {
                        $form->get(self::MODULE_CONFIG)->addError(new FormError('expect moduleConfigs to be array object'));
                        return;
                    }

                    // validate video player configuration
                    if($user->hasVideoModule()) {
                        if(!array_key_exists(self::VIDEO_MODULE, $moduleConfigs)) {
                            $form->get(self::MODULE_CONFIG)->addError(new FormError('expect moduleConfigs to contain valid video players configuration'));
                            return;
                        }

                        $this->validateVideoConfig($moduleConfigs[self::VIDEO_MODULE], $form);
                    }
                    else {
                        if(array_key_exists(self::VIDEO_MODULE, $moduleConfigs)){
                            $form->get(self::MODULE_CONFIG)->addError(new FormError('This user does not have video module enabled'));
                            return;
                        }
                    }
                }
            );
        }

        //validate 'settings' field submitted by publisher
        //also merge all changes to current 'settings' of publisher (ui only submit with patched settings)
        if($this->userRole instanceof BrokerInterface) {
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    /** @var BrokerInterface $broker */
                    $broker = $form->getData();
                    $this->oldSettings = $broker->getSettings();
                });

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) {
                    $form = $event->getForm();
                    /** @var BrokerInterface $broker */
                    $broker = $form->getData();
                    //this settings is only patched settings
                    $settings = $broker->getSettings();

                    // 1. validate 'settings' field submitted by publisher
                    if (!isset($settings['view']['report']['performance']['adTag'])) {
                        $form->addError(new FormError("either 'view' or 'report' or 'performance' or 'adTag' field is missing!"));
                        return;
                    }

                    $adTagConfigs = $settings['view']['report']['performance']['adTag'];

                    foreach ($adTagConfigs as $adTagConfig) {
                        // keys 'key', 'label, 'show' are required
                        if (!isset($adTagConfig['key'])
                            || !isset($adTagConfig['label'])
                            || !isset($adTagConfig['show'])
                        ) {
                            $form->addError(new FormError("'key or label or show' field is missing!"));
                            break;
                        }

                        // all values of 'key' need to be supported
                        if (!in_array($adTagConfig['key'], self::$REPORT_SETTINGS_ADTAG_KEY_VALUES)) {
                            $form->addError(new FormError("key '" . $adTagConfig['key'] . "' is not supported yet!"));
                            break;
                        }

                        // value 'show' need to be boolean
                        if (!is_bool($adTagConfig['show'])) {
                            $form->addError(new FormError("value of show for '" . $adTagConfig['key'] . "' must be boolean!"));
                            break;
                        }
                    }


                    // 2. also merge all changes to current 'settings' of publisher (ui only submit with patched settings)
                    //    if not patch, old_settings (unchanged) will be removed
                    ////checking current settings: not existed or invalid => do nothing, using from ui
                    if($this->oldSettings !== null
                        && isset($this->oldSettings['view']['report']['performance']['adTag'])
                        && count($this->oldSettings['view']['report']['performance']['adTag']) > 0
                    ) {
                        $newSettings = array_map(function($settingItem) use ($settings) {
                            $settingItems = $settings['view']['report']['performance']['adTag'];
                            foreach($settingItems as $idx => $si) {
                                if($settingItem['key'] === $si['key']) {
                                    return $si;
                                }
                            }

                            return $settingItem;
                        }, $this->oldSettings['view']['report']['performance']['adTag']);
                        $this->oldSettings['view']['report']['performance']['adTag'] = $newSettings;
                        $broker->setSettings($this->oldSettings);
                    }
                }
            );
        }
    }

    protected function validateVideoConfig($videoConfig, FormInterface $form)
    {
        if(!is_array($videoConfig)) {
            $form->get(self::MODULE_CONFIG)->addError(new FormError('Invalid video configuration'));
            return;
        }

        if(!array_key_exists(self::VIDEO_PLAYERS, $videoConfig)) {
            $form->get(self::MODULE_CONFIG)->addError(new FormError('expect video players configuration'));
            return;
        }

        $videoPlayers = $videoConfig[self::VIDEO_PLAYERS];
        if(!is_array($videoPlayers)) {
            $form->get(self::MODULE_CONFIG)->addError(new FormError('Invalid video players configuration'));
            return;
        }

        if(count($videoPlayers) < 1) {
            $form->get(self::MODULE_CONFIG)->addError(new FormError('No player found'));
            return;
        }

        foreach($videoPlayers as $player){
//            if(!is_array($player) || !array_key_exists('name', $player)) {
//                $form->get(self::MODULE_CONFIG)->addError(new FormError('Invalid video players configuration'));
//                return;
//            }
//
//            $name = $player['name'];

            if(!in_array($player, $this->listPlayers)) {
                $form->get(self::MODULE_CONFIG)->addError(new FormError(sprintf('players %s is not supported', $player)));
                return;
            }

//            if(count(array_keys($player)) > 2) {
//                $form->get(self::MODULE_CONFIG)->addError(new FormError('video players configuration should not contain extra field'));
//                return;
//            }
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => User::class,
                'validation_groups' => ['Admin', 'Default'],
            ])
        ;
    }

    public function getName()
    {
        return 'd_tag_form_admin_api_user';
    }
}