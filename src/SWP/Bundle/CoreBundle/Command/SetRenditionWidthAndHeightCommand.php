<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Core Bundle.
 *
 * Copyright 2018 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2018 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetRenditionWidthAndHeightCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'swp:article:rendition:populate';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Sets width and height for renditions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $renditionRepository = $this->getContainer()->get('swp.repository.image_rendition');

        $renditions = $renditionRepository->findBy(['width' => 0, 'height' => 0, 'name' => 'original']);
        foreach ($renditions as $rendition) {
            if (null !== ($width = $rendition->getImage()->getWidth()) && null !== ($height = $rendition->getImage()->getHeight())) {
                $rendition->setWidth($width);
                $rendition->setHeight($height);
            }
        }

        $renditionRepository->flush();

        $output->writeln('<bg=green;options=bold>Done. Set width and height for '.\count($renditions).' renditions.</>');
    }
}
