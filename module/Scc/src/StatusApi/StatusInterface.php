<?php

namespace StatusApi;

/**
 * Interface for status posts
 */
interface StatusInterface {

    const TYPE_STATUS = 'status';
    const TYPE_IMAGE = 'image';
    const TYPE_LINK = 'link';

    public function setId($id);

    public function setType($type);

    public function setText($text);

    public function setUser($user);

    public function setTimestamp($timestamp);

    public function getId();

    public function getType();

    public function getText();

    public function getUser();

    public function getTimestamp();

}