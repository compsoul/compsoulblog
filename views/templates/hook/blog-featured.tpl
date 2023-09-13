{*
/**
 ***********************************************************************************************
 *                             COMPSOUL BLOG MODULE - LEGAL NOTICE                             *
 ***********************************************************************************************
 * The Compsoul Blog module ("the Module") is the intellectual property of Daniel Dziedzic,
 * trading under the name "Compsoul". By downloading, installing, or using the Module, you
 * ("the User") agree to be bound by the following terms and conditions:
 *
 * 1. Modification and Customization:
 * The User is permitted to modify and customize the Module for their own use, provided that
 * any substantial modifications or alterations are made in consultation with the original
 * creator, Daniel Dziedzic. The User must seek approval for any significant changes to the Module.
 *
 * 2. Non-Profit Use:
 * The Module can be used by non-profit organizations without any restrictions, subject to
 * compliance with all other terms and conditions specified herein.
 *
 * 3. Prohibited Resale:
 * The User is strictly prohibited from selling, leasing, licensing, or distributing the Module
 * in any form, either as it is or in modified versions, for commercial or monetary gain. The Module
 * is intended for non-commercial use only.
 *
 * 4. Consultation with the Creator:
 * Any attempts to modify, alter, or distribute the Module, in part or in whole, must be communicated
 * and approved by the original creator, Daniel Dziedzic. For any inquiries or consultation, please
 * contact the creator via email at daniel@compsoul.pl.
 *
 * 5. Creator Information:
 * The Module has been created by Daniel Dziedzic, trading under the name "Compsoul". For more
 * information about the creator and their work, please visit the official website at compsoul.pl
 * (for Polish) or compsoul.dev (for English).
 *
 * 6. Display of Creator Information:
 * The User agrees to include a visible and accessible link to the creator's website, compsoul.pl or
 * compsoul.dev, at the bottom of the list of posts on the frontend of their website using the Module.
 * The link should be displayed prominently and must be maintained at all times.
 *
 * Contact:
 * For technical support or to obtain permission for Module modifications, please contact the
 * creator:
 *
 * @author Daniel Dziedzic <daniel@compsoul.pl>
 * @copyright Compsoul
 * @license Free License with Attribution
 * @link https://compsoul.pl
 * @link https://compsoul.dev
 *
 * The User acknowledges and agrees that any use of the Module is subject to the terms and conditions
 * outlined in this legal notice. Failure to comply with these terms may result in legal action.
 ***********************************************************************************************
 *                                     END OF LEGAL NOTICE                                     *
 ***********************************************************************************************
 */
*}

{if !empty($blog_pages)}
<section id="module-blog-carousel" class="module-blog module-blog-carousel module container">
  <div class="blog-container">
    <h2 class="blog-heading">{l s='Blog' d='Modules.Compsoulblog.Front'}</h2>
    <div class="blog-carousel">
      <ul class="blog-list listing-blog">
        {foreach from=$blog_pages item=blog_page}
          <li class="blog-item listing-item">
            <h3 class="blog-headline listing-headline content">
              <a class="blog-link listing-link" href="{$link->getCMSLink($blog_page.id_cms)}" title="{$blog_page.meta_title|escape:'htmlall':'UTF-8'}">
                {$blog_page.meta_title|escape:'htmlall':'UTF-8'}
              </a>
            </h3>

            {if !empty($blog_page.thumbnail) || isset($blog_page.id_cms_category)}
              <div class="blog-top listing-top{if !empty($blog_page.thumbnail)} listing-top-img{/if}">
                {if !empty($blog_page.thumbnail)}
                  <figure class="blog-image listing-image">
                    <a class="blog-link listing-link" href="{$link->getCMSLink($blog_page.id_cms)}" title="{$blog_page.meta_title}">
                      <img class="blog-img listing-img" src="{$img_path}/{$blog_page.thumbnail}" alt="{$blog_page.meta_title}" loading="lazy">
                    </a>
                  </figure>
                {/if}

                {if isset($blog_page.id_cms_category)}
                  <nav class="blog-categories listing-categories">
                    <ul class="blog-categories-list listing-categories-list">
                      {foreach from=$categories item=category}
                        {if $category.id_cms_category == $blog_page.id_cms_category}
                          <li class="blog-categorie listing-categorie">
                            <a class="blog-categorie-link listing-categorie-link"{if !empty($category.meta_title)} title="{$category.meta_title}"{/if} href="{$link->getCMSCategoryLink($category.id_cms_category)}">{$category.name}</a>
                          </li>
                        {/if}
                      {/foreach}
                    </ul>
                  </nav>
                {/if}
              </div>
            {/if}

            <time class="blog-time listing-time" datetime="{$blog_page.date_upd|date_format:"%Y-%m-%dT%H:%M:%S"}">
              {$blog_page.date_upd|date_format:"%d.%m.%Y"}
            </time>

            {if !empty($blog_page.summary)}
              <div class="blog-description listing-description content">
                {$blog_page.summary|escape:'htmlall':'UTF-8'}
              </div>
            {/if}

            <a class="blog-more listing-more more icon" title="{$blog_page.meta_title|escape:'htmlall':'UTF-8'}" href="{$link->getCMSLink($blog_page.id_cms)}">
              {l s='Read more' d='Modules.Compsoulblog.Front'}
            </a>
          </li>
        {/foreach}
      </ul>
    </div>
    {$more = $categories|@array_shift}
    {if !empty($more)}
      <a href="{$link->getCMSCategoryLink($more.id_cms_category)}" class="blog-carousel-more more icon"{if !empty($more.meta_title)} title="{$more.meta_title}"{/if}>{l s='See all blog posts' d='Modules.Compsoulblog.Front'}</a>
    {/if}

    <button class="blog-carousel-button blog-carousel-next icon">
      <span class="sr-only">{l s='Next' d='Shop.Theme.Global'}</span>
    </button>
    <button class="blog-carousel-button blog-carousel-prev icon">
      <span class="sr-only">{l s='Previous' d='Shop.Theme.Global'}</span>
    </button>
  </div>
</section>
{/if}
