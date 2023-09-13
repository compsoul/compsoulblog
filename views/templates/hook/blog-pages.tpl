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

<section class="container module module-blog module-blog-listing">
  <div class="blog-container">
    <h2 class="blog-title">
      {if isset($cms_category.name)}
        {$cms_category.name}
      {elseif isset($page.meta.title)}
        {$page.meta.title}
      {else}
        {l s='Blog' d='Modules.Compsoulblog.Front'}
      {/if}
      {if !empty($blog_pages)}
        <span class="blog-news-number">
          {count($blog_pages)} {l s='number of entries' d='Modules.Compsoulblog.Front'}
        </span>
      {/if}
    </h2>

    {if !empty($cms_category.description)}
      <div class="blog-categorie-description content">
        {$cms_category.description}
      </div>
    {/if}

    {if !empty($categories)}
      <nav class="blog-nav">
        <h2 class="blog-nav-heading">{l s='Category:' d='Modules.Compsoulblog.Front'}</h2>
        <button class="blog-nav-button icon">{l s='Select a category' d='Modules.Compsoulblog.Front'}</button>
        <ul class="blog-nav-list">
          {foreach from=$categories item=category}
            <li class="blog-nav-item">
              <a class="blog-nav-link" href="{$link->getCMSCategoryLink($category.id_cms_category)}"{if !empty($category.meta_title)} title="{$category.meta_title}"{/if}>
                {$category.name}
              </a>
            </li>
          {/foreach}
        </ul>
      </nav>
    {/if}

    {if !empty($blog_pages)}
      <ul id="blog-compsoul" class="blog-list listing-blog">
        {foreach from=$blog_pages item=blog_page}
          <li class="blog-item listing-item">
            <h3 class="blog-headline listing-headline content">
              <a class="blog-link listing-link" href="{$link->getCMSLink($blog_page.id_cms)}" title="{$blog_page.meta_title}">
                {$blog_page.meta_title}
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

            <a class="blog-more listing-more more icon" title="{$blog_page.meta_title}" href="{$link->getCMSLink($blog_page.id_cms)}">
              {l s='Read more' d='Modules.Compsoulblog.Front'}
            </a>
          </li>
        {/foreach}
      </ul>

    {/if}
  </div>
  <aside class="blog-created">
    <p>
      {l s='Created by:' d='Modules.Compsoulblog.Front'}
      <a class="blog-created-link" title="{l s='Web Design and Development Services: Websites, E-commerce Stores, Website Templates, Web Modules and Free Webpages.' d='Modules.Compsoulblog.Front'}" href="{l s='https://compsoul.dev' d='Modules.Compsoulblog.Front'}" rel="_blank">{l s='compsoul.dev' d='Modules.Compsoulblog.Front'}</a>
    </p>
  </aside>
</section>