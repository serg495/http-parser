<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Lots;


use App\Repositories\DBRepositories\Dictionaries\Requirements as RequirementsDictionary;
use App\Services\Parsers\Http\_Core\BaseModel;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class Requirements extends BaseModel
{
    /**
     * @var array
     */
    protected $requirements;

    /**
     * @var int
     */
    protected $index = 0;

    public function __construct(Crawler $crawler)
    {
        parent::__construct($crawler);

        $this->requirements = RequirementsDictionary::make()->getAllRequirements();
    }

    public function handle(): array
    {
        $this->parseAdvantages();
        $this->parseRequirements();
        $this->parseLimits();

        return $this->getModel();
    }

    /**
     * @param int $requirementId
     * @param string|null $content
     */
    protected function addRequirement(int $requirementId, string $content = null)
    {
        $this->model[$this->index] = [
            'requirement_id' => $requirementId,
            'content' => $content,
        ];

        $this->index++;
    }

    protected function parseAdvantages(): void
    {
        $advantageXPath = '//td[contains(text(), \'Преимущества\')]/following-sibling::td';

        $advantagesString = $this->crawler->filterXPath($advantageXPath)->text();
        $advantages = preg_split('/\s{2,}/', trim($advantagesString));

        foreach ($advantages as $advantage) {
            foreach ($this->requirements as $requirement) {
                if (Str::contains($advantage, $requirement->name)) {
                    $this->addRequirement($requirement->id);
                }
            }
        }
    }

    protected function parseRequirements()
    {
        $requirementXPath = '//td[contains(text(), \'Требования\')]/following-sibling::td';

        $adaptedRequirements = [];
        $requirementsString = $this->crawler->filterXPath($requirementXPath)->text();
        $currentRequirements = preg_split('/\s{2,}/', trim($requirementsString));

        foreach ($currentRequirements as $index => $requirement) {
            if ($index % 2 === 0) {
                $adaptedRequirements[$index]['name'] = $requirement;
            } else {
                $adaptedRequirements[$index - 1]['content'] = $requirement;
            }
        }

        foreach ($adaptedRequirements as $adaptedRequirement) {
            foreach ($this->requirements as $requirement) {
                if (Str::contains($adaptedRequirement['name'], $requirement->name)) {
                    $this->addRequirement($requirement->id, $adaptedRequirement['content']);
                }
            }
        }
    }

    protected function parseLimits()
    {
        $requirementXPath = '//td[contains(text(), \'Ограничения\')]/following-sibling::td';

        $adaptedLimits = [];
        $limitsString = $this->crawler->filterXPath($requirementXPath)->text();
        $currentLimits = preg_split('/\s{2,}/', trim($limitsString));

        foreach ($currentLimits as $index => $limit) {
            if ($index % 2 === 0) {
                $adaptedLimits[$index]['name'] = $limit;
            } else {
                $adaptedLimits[$index - 1]['content'] = $limit;
            }
        }

        foreach ($adaptedLimits as $adaptedLimit) {
            foreach ($this->requirements as $requirement) {
                if (Str::contains($adaptedLimit['name'], $requirement->name)) {
                    $this->addRequirement($requirement->id, $adaptedLimit['content']);
                }
            }
        }
    }
}
