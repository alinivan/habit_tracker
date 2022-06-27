<?php

namespace Core\Builder;

use Core\View\ViewManager;

class Modal extends ViewManager
{
    private array $modal;

    public function setTitle(string $title)
    {
        $this->modal['title'] = $title;
    }

    public function addBtn(array $fields)
    {
        $this->modal['buttons'][] = [
            'label' => $fields['label'] ?? '',
            'href' => $fields['href'] ?? 'javascript:void(0)',
            'onclick' => $fields['onclick'] ?? ''
        ];
    }

    public function setContent(string $content) {
        $this->modal['content'] = $content;
    }

    public function getHtml(): string
    {
        return $this->renderView('core/builder/modal.html.twig', ['modal' => $this->modal]);
    }
}