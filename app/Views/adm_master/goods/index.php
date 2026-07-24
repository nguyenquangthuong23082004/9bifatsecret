<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <ul class="last">
        <li><a href="<?= base_url('AdmMaster/goods/form') ?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> <span class="txt">상품 등록</span></a></li>
    </ul>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form name="search" id="search" method="get">
    <header id="headerContents">
        <select name="search_category" class="input_select" style="width:112px">
            <option value="product_name" <?= ($search_category ?? '') == 'product_name' ? 'selected' : '' ?>>상품명</option>
            <option value="product_manager" <?= ($search_category ?? '') == 'product_manager' ? 'selected' : '' ?>>담당자</option>
            <option value="product_code" <?= ($search_category ?? '') == 'product_code' ? 'selected' : '' ?>>코드검색</option>
        </select>

        <input type="text" name="search_name" value="<?= esc($search_name ?? '') ?>" class="input_txt" style="width:240px" placeholder="검색어 입력" />
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> <span class="txt">검색하기</span></button>
    </header>
</form>

<div class="listWrap">
    <div class="listTop">
        <div class="left">
            <p class="schTxt">■ 총 <?= $totalCount ?>개의 목록이 있습니다.</p>
        </div>
    </div>

    <div class="listBottom">
        <form name="frm" id="frm">
            <table cellpadding="0" cellspacing="0" summary="" class="listTable">
                <caption></caption>
                <colgroup>
                    <col width="1%" />
                    <col width="1%" />
                    <col width="1%" />						
                    <col width="*" />
                    <col width="1%" />
                    <col width="1%" />
                </colgroup>
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>카테고리</th>
                        <th>썸네일이미지</th>
                        <th>타이틀</th>
                        <th>사용유무</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="6" style="text-align:center; height:100px">검색된 결과가 없습니다.</td></tr>
                    <?php else: ?>
                        <?php 
                        $num = $totalCount - (($pg - 1) * 10);
                        foreach ($list as $row): 
                        ?>
                        <tr style="height:50px">
                            <td rowspan="2"><?= $num-- ?></td>
                            <td rowspan="2" class="tac">
                                <a href="<?= base_url('AdmMaster/goods/form/'.$row['idx']) ?>"><?= esc($row['product_code_name_1'] ?? '') ?> / <?= esc($row['product_code_name_2'] ?? '') ?></a>
                            </td>
                            <td class="tac">
                                <?php if (!empty($row['ufile1'])): ?>
                                    <a href="<?= base_url('data/product/'.$row['ufile1']) ?>" target="_blank"><img src="<?= base_url('data/product/'.$row['ufile1']) ?>" style="max-width:150px;max-height:100px"></a>
                                <?php endif; ?>
                            </td>
                            <td class="tal" style="font-weight:bold">
                                <a href="<?= base_url('AdmMaster/goods/form/'.$row['idx']) ?>">
                                    <?= esc($row['goods_name_ko']) ?> / <?= esc($row['goods_name_en']) ?>
                                </a>
                            </td>
                            <td class="tac">
                                <?php if ($row['useYN'] == 'Y'): ?>
                                    <font color='blue'>사용</font>
                                <?php else: ?>
                                    <font color='red'>사용안함</font>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['regdate'] ?></td>
                        </tr>
                        <tr style="height:45px">
                            <th style="background:#fafafa; border:1px solid #dddddd; padding:10px 0; font-size:14px; font-weight:bold; color:#464646; text-align:center;">
                                한줄설명
                            </th>
                            <td colspan="3" style="background:#fafafa; text-align:left; padding-left:15px; font-weight:bold">
                                <?= esc($row['oneinfo_ko'] ?? '') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>

    <div class="paging">
        <ul>
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <li class="<?= $pg == $i ? 'active' : '' ?>"><a href="?pg=<?= $i ?>&search_category=<?= $search_category ?>&search_name=<?= $search_name ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
