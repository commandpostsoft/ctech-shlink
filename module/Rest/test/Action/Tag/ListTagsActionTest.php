<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\Rest\Action\Tag;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Pagerfanta\Adapter\ArrayAdapter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shlinkio\Shlink\Common\Paginator\Paginator;
use Shlinkio\Shlink\Core\Tag\Entity\Tag;
use Shlinkio\Shlink\Core\Tag\TagServiceInterface;
use Shlinkio\Shlink\Rest\Action\Tag\ListTagsAction;
use Shlinkio\Shlink\Rest\Entity\ApiKey;

use function count;

class ListTagsActionTest extends TestCase
{
    private ListTagsAction $action;
    private MockObject & TagServiceInterface $tagService;

    protected function setUp(): void
    {
        $this->tagService = $this->createMock(TagServiceInterface::class);
        $this->action = new ListTagsAction($this->tagService);
    }

    #[Test]
    public function returnsBaseDataWhenStatsAreNotRequested(): void
    {
        $tags = [new Tag('foo'), new Tag('bar')];
        $tagsCount = count($tags);
        $this->tagService->expects($this->once())->method('listTags')->with(
            $this->anything(),
            $this->isInstanceOf(ApiKey::class),
        )->willReturn(new Paginator(new ArrayAdapter($tags)));

        /** @var JsonResponse $resp */
        $resp = $this->action->handle($this->requestWithApiKey());
        $payload = $resp->getPayload();

        self::assertEquals([
            'tags' => [
                'data' => $tags,
                'pagination' => [
                    'currentPage' => 1,
                    'pagesCount' => 1,
                    'itemsPerPage' => 10,
                    'itemsInCurrentPage' => $tagsCount,
                    'totalItems' => $tagsCount,
                ],
            ],
        ], $payload);
    }

    private function requestWithApiKey(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals()->withAttribute(ApiKey::class, ApiKey::create());
    }
}
