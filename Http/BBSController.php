<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BBSController extends Controller
{
   public function index() {

    require_once('boardDao.php');
    require_once('tools.php');

    $currentPage = requestValue("page");
    // http://localhost/bbs/board.php?page=-3
    if($currentPage < 1) 
    	$currentPage = 1;

	$dao = new boardDao();

	// 집단함수, aggregate function
	// select count(*) from board;
	$totalCount = $dao->getTotalCount();

	if($totalCount > 0) {
	
			$startPage = floor(($currentPage-1)/NUM_PAGE_LINKS)*NUM_PAGE_LINKS+1;	
			$endPage = $startPage + NUM_PAGE_LINKS - 1;
			$totalMsgs = $dao->getTotalCount();
			$totalPages = ceil($totalMsgs/NUM_LINES);
			if ($endPage > $totalPages)
				$endPage = $totalPages;

			$prev = false;
			$next = false;

			if ($startPage > 1) $prev = true;
	
			$startRecord = ($currentPage-1)*NUM_LINES;	
			//$msgs = $dao->getManyMsgs();
			$msgs = $dao->getMsgs4Page($startRecord, NUM_LINES);
		}

		return view('bbs.board')
			       ->with('startPage', $startPage)
			       ->with('endPage', $endPage)
			       ->with('totalPages', $totalPages)
			       ->with('currentPage', $currentPage)
			       ->with('msgs', $msgs);

   }

   public function show() {

		// 특정 글의 상세보기 
		require_once("tools.php");
		require_once("boardDao.php");

		/* 
			request에서 글의 id를 추출
			해당 번호의 글을 읽고, 조회 수 1 증가 
			읽은 글을 출력한다.

		*/
		$id = requestValue("id");
		$page = requestValue("page");
		$dao = new boardDao();
		$msg = $dao->getMsg($id);	
		$dao->increaseHits($id);

		return view('bbs.view')->with('id', $id)->with('page', $page)->with('msg', $msg);

   }

   public function create() { //글 쓰기 form

	return view('bbs.write_form');

   }

   public function store() { //post방식 /글쓰기처리

	require_once('tools.php');
    require_once('boardDao.php');

    $title = requestValue('title');
    $writer = requestValue('writer');
    $content = requestValue('content');

    $page = requestValue('page');

    if($title && $writer && $content){
        $bdao = new boardDao();
        $bdao->insertMsg($title, $writer, $content);
		// okGo("정상적으로 입력되었습니다.", "bbs?page=$page");
		return redirect('bbs?page=$page')->with('message',$title.'이 정상입력됨');
    }else{
        errorBack('모든 항목이 빈칸 없이 입력되어야 합니다.');
    }

   }

   public function edit() { //글 수정 form
	require_once("boardDao.php");
	require_once("tools.php");
	/*
		1. 클라이언트가 송신한 num 값을 읽는다.
		2. 그 값으로 해당하는 게시글을 읽는다.
		3. 그 게시글 정보를 이용해 html을 동적으로 생성한다. 
	*/
		$num = requestValue("num");
    	$page = requestValue("page");
		$dao = new boardDao();
		$row = $dao->getMsg($num);

		return view('bbs.modify_form')->with('num',$num)->with('page',$page)->with('row',$row);
	
   }

   public function update() { //post방식 /글수정처리
    require_once('tools.php');
    require_once('boardDao.php');

    $num = requestValue('num');
    $title = requestValue('title');
    $writer = requestValue('writer');
    $content = requestValue('content');

    $page = requestValue('page');

    if($num && $title && $writer && $content){
        $bdao = new boardDao();
        $bdao->updateMsg($num, $title, $writer, $content);
		//okGo("정상적으로 수정되었습니다.", "bbs?page=$page");
		return redirect('bbs?page=$page')->with('message',$num.'글이 정상수정됨');
    }else{
        errorBack('모든 항목이 빈칸 없이 입력되어야 합니다.');
    }

   }

   public function destroy() {
 	require_once("tools.php");
	require_once("BoardDao.php");

	$num = requestValue("num");
	$page = requestValue("page");
	$dao = new BoardDao();
	$dao->deleteMsg($num);

	return redirect('bbs?page=$page')->with('message', $num . "번 글이 삭제되었습니다.");
   }


}
