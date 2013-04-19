<?php

namespace MessageBoard\Service;

interface BoardServiceAware {

    public function setBoardService(BoardService $service);
}