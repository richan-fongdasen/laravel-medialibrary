<?php

namespace Spatie\MediaLibrary\Test\PathGenerator;

use Spatie\MediaLibrary\Conversion\ConversionCollectionFactory;
use Spatie\MediaLibrary\PathGenerator\BasePathGenerator;
use Spatie\MediaLibrary\Test\TestCase;
use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator;

class BasePathGeneratorTest extends TestCase
{
    protected $config;

    /**
     * @var \Spatie\MediaLibrary\Media
     */
    protected $media;

    /**
     * @var \Spatie\MediaLibrary\Conversion\Conversion
     */
    protected $conversion;

    /**
     * @var LocalUrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var BasePathGenerator
     */
    protected $pathGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->config = app('config');

        // because BaseUrlGenerator is abstract we'll use LocalUrlGenerator to test the methods of base
        $this->urlGenerator = new LocalUrlGenerator($this->config);

        // but we'll use a custom pathGenerator
        $this->pathGenerator = new CustomPathGenerator();

        $this->urlGenerator->setPathGenerator($this->pathGenerator);
    }

    /**
     * @test
     */
    public function it_can_get_the_custom_path_for_media_without_conversions()
    {
        $media = $this->testModel->addMedia($this->getTestFilesDirectory('test.jpg'))->toMediaLibrary();

        $this->urlGenerator->setMedia($media);

        $pathRelativeToRoot = md5($media->id) . '/' . $media->file_name;

        $this->assertEquals($pathRelativeToRoot, $this->urlGenerator->getPathRelativeToRoot());
    }

    /**
     * @test
     */
    public function it_can_get_the_custom_path_for_media_with_conversions()
    {
        $media = $this->testModelWithConversion->addMedia($this->getTestFilesDirectory('test.jpg'))->toMediaLibrary();
        $conversion = ConversionCollectionFactory::createForMedia($media)->getByName('thumb');

        $this->urlGenerator
            ->setMedia($media)
            ->setConversion($conversion);

        $pathRelativeToRoot = md5($media->id).'/c/'.$conversion->getName().'.'.$conversion->getResultExtension($media->extension);

        $this->assertEquals($pathRelativeToRoot, $this->urlGenerator->getPathRelativeToRoot());
    }
}
