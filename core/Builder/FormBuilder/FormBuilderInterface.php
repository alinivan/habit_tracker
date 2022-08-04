<?php

namespace Core\Builder\FormBuilder;

interface FormBuilderInterface
{
    public function addInput(array $fields): void;

    public function addSelect(array $fields): void;

    public function setAction(string $action): void;

    public function setMethod(string $method): void;

    public function setTitle(string $title): void;

    public function setSubmit(array $fields): void;

    public function getId(): string;

    public function getHtml(): string;
}