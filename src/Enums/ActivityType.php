<?php


namespace Blog\Enums;


class ActivityType extends Enum
{
    public const LIKE = 'like';
    public const DISLIKE = 'dislike';
    public const FAVOURITE = 'favourite';
    public const LATER = 'later';
    public const INAPPROPRIATE = 'inappropriate';
}
