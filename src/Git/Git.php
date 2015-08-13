<?php
namespace PointingBreed\Git;

interface Git
{
    public function diff($pathToRepository, $shaFrom, $shaTo);
}
