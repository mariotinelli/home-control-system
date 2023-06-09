<?php

namespace Tests;

use Illuminate\Foundation\Testing\{RefreshDatabase, TestCase as BaseTestCase};

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected bool $seed = true;
}
