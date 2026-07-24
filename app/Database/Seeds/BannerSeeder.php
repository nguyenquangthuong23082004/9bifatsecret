<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run()
    {
        $db      = \Config\Database::connect();
        $db->table('tbl_banner_mst')->truncate();
        $builder = $db->table('tbl_banner_mst');

        // Main visual banners
        $visualBanners = [
            [
                'banner_position' => 'main_visual',
                'title'           => '우리 가문의 긍지와 가풍을 담은 재실,<br class="only_web">주인의 개성과 정신을 반영한 집을 짓습니다.',
                'sub_title'       => '',
                'image_alt'       => '전통 한옥 기와 지붕 전경',
                'pc_image'        => '/assets/img/main/main_v01.webp',
                'mobile_image'    => '',
                'link_url'        => '',
                'link_target'     => '_self',
                'onum'            => 30,
                'status'          => 'Y',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'banner_position' => 'main_visual',
                'title'           => '전통의 맥을 이어<br class="only_web">주인을 닮은 집을 짓습니다.',
                'sub_title'       => '',
                'image_alt'       => '한옥 처마와 단청',
                'pc_image'        => '/assets/img/main/main_v02.webp',
                'mobile_image'    => '',
                'link_url'        => '',
                'link_target'     => '_self',
                'onum'            => 20,
                'status'          => 'Y',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'banner_position' => 'main_visual',
                'title'           => '전통의 맥을 이어<br class="only_web">주인을 닮은 집을 짓습니다.',
                'sub_title'       => '',
                'image_alt'       => '한옥 처마와 단청',
                'pc_image'        => '/assets/img/main/main_v03.webp',
                'mobile_image'    => '',
                'link_url'        => '',
                'link_target'     => '_self',
                'onum'            => 10,
                'status'          => 'Y',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        // Main CTA banner
        $ctaBanners = [
            [
                'banner_position' => 'main_cta',
                'title'           => '한옥에 대한 바른 철학으로 재료에서<br class="only_web">마감까지 정성을<br class="only_mo"> 다합니다',
                'sub_title'       => '으뜸이의집',
                'image_alt'       => '',
                'pc_image'        => '/assets/img/main/cta_banner.png',
                'mobile_image'    => '/assets/img/main/cta_banner_m.png',
                'link_url'        => '',
                'link_target'     => '_self',
                'onum'            => 10,
                'status'          => 'Y',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]
        ];

        // Insert all
        foreach (array_merge($visualBanners, $ctaBanners) as $banner) {
            $builder->insert($banner);
        }
    }
}
