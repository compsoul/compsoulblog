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

{foreach from=$blog_pages item=blog_page}
  <section class="container module module-blog blog-page">
    {if $blog_page.headingFirstSection ||
        $blog_page.firstColumnHeadingFirstSection ||  
        $blog_page.firstColumnContentFirstSection ||
        $blog_page.secondColumnHeadingFirstSection ||  
        $blog_page.secondColumnContentFirstSection ||
        $blog_page.thirdColumnHeadingFirstSection || 
        $blog_page.thirdColumnContentFirstSection
    }
      <{if $blog_page.headingFirstSection}article{else}div{/if} class="blog-layout blog-layout-first">
        <div class="blog-layout-container">
          {if $blog_page.headingFirstSection}<h2 class="blog-layout-heading">{$blog_page.headingFirstSection}</h2>{/if}
          {if $blog_page.firstColumnHeadingFirstSection || $blog_page.firstColumnContentFirstSection}
            <div class="blog-layout-content content">
              {if $blog_page.firstColumnHeadingFirstSection}<h3 class="blog-layout-headline">{$blog_page.firstColumnHeadingFirstSection}</h3>{/if}
              {if $blog_page.firstColumnContentFirstSection }{$blog_page.firstColumnContentFirstSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.secondColumnHeadingFirstSection || $blog_page.secondColumnContentFirstSection}
            <div class="blog-layout-content content">
              {if $blog_page.secondColumnHeadingFirstSection}<h3 class="blog-layout-headline">{$blog_page.secondColumnHeadingFirstSection}</h3>{/if}
              {if $blog_page.secondColumnContentFirstSection }{$blog_page.secondColumnContentFirstSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.thirdColumnHeadingFirstSection || $blog_page.thirdColumnContentFirstSection}
            <div class="blog-layout-content content">
              {if $blog_page.thirdColumnHeadingFirstSection}<h3 class="blog-layout-headline">{$blog_page.thirdColumnHeadingFirstSection}</h3>{/if}
              {if $blog_page.thirdColumnContentFirstSection }{$blog_page.thirdColumnContentFirstSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
        </div>
      </{if $blog_page.headingFirstSection}article{else}div{/if}>
    {/if}

    {if $blog_page.headingSecondSection ||
        $blog_page.firstColumnHeadingSecondSection ||  
        $blog_page.firstColumnContentSecondSection ||
        $blog_page.secondColumnHeadingSecondSection ||  
        $blog_page.secondColumnContentSecondSection ||
        $blog_page.thirdColumnHeadingSecondSection || 
        $blog_page.thirdColumnContentSecondSection
    }
      <{if $blog_page.headingSecondSection}article{else}div{/if} class="blog-layout blog-layout-first">
        <div class="blog-layout-container">
          {if $blog_page.headingSecondSection}<h2 class="blog-layout-heading">{$blog_page.headingSecondSection}</h2>{/if}
          {if $blog_page.firstColumnHeadingSecondSection || $blog_page.firstColumnContentSecondSection}
            <div class="blog-layout-content content">
              {if $blog_page.firstColumnHeadingSecondSection}<h3 class="blog-layout-headline">{$blog_page.firstColumnHeadingSecondSection}</h3>{/if}
              {if $blog_page.firstColumnContentSecondSection }{$blog_page.firstColumnContentSecondSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.secondColumnHeadingSecondSection || $blog_page.secondColumnContentSecondSection}
            <div class="blog-layout-content content">
              {if $blog_page.secondColumnHeadingSecondSection}<h3 class="blog-layout-headline">{$blog_page.secondColumnHeadingSecondSection}</h3>{/if}
              {if $blog_page.secondColumnContentSecondSection }{$blog_page.secondColumnContentSecondSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.thirdColumnHeadingSecondSection || $blog_page.thirdColumnContentSecondSection}
            <div class="blog-layout-content content">
              {if $blog_page.thirdColumnHeadingSecondSection}<h3 class="blog-layout-headline">{$blog_page.thirdColumnHeadingSecondSection}</h3>{/if}
              {if $blog_page.thirdColumnContentSecondSection }{$blog_page.thirdColumnContentSecondSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
        </div>
      </{if $blog_page.headingSecondSection}article{else}div{/if}>
    {/if}

    {if $blog_page.headingThirdSection ||
        $blog_page.firstColumnHeadingThirdSection ||  
        $blog_page.firstColumnContentThirdSection ||
        $blog_page.secondColumnHeadingThirdSection ||  
        $blog_page.secondColumnContentThirdSection ||
        $blog_page.thirdColumnHeadingThirdSection || 
        $blog_page.thirdColumnContentThirdSection
    }
      <{if $blog_page.headingThirdSection}article{else}div{/if} class="blog-layout blog-layout-first">
        <div class="blog-layout-container">
          {if $blog_page.headingThirdSection}<h2 class="blog-layout-heading">{$blog_page.headingThirdSection}</h2>{/if}
          {if $blog_page.firstColumnHeadingThirdSection || $blog_page.firstColumnContentThirdSection}
            <div class="blog-layout-content content">
              {if $blog_page.firstColumnHeadingThirdSection}<h3 class="blog-layout-headline">{$blog_page.firstColumnHeadingThirdSection}</h3>{/if}
              {if $blog_page.firstColumnContentThirdSection }{$blog_page.firstColumnContentThirdSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.secondColumnHeadingThirdSection || $blog_page.secondColumnContentThirdSection}
            <div class="blog-layout-content content">
              {if $blog_page.secondColumnHeadingThirdSection}<h3 class="blog-layout-headline">{$blog_page.secondColumnHeadingThirdSection}</h3>{/if}
              {if $blog_page.secondColumnContentThirdSection }{$blog_page.secondColumnContentThirdSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
          {if $blog_page.thirdColumnHeadingThirdSection || $blog_page.thirdColumnContentThirdSection}
            <div class="blog-layout-content content">
              {if $blog_page.thirdColumnHeadingThirdSection}<h3 class="blog-layout-headline">{$blog_page.thirdColumnHeadingThirdSection}</h3>{/if}
              {if $blog_page.thirdColumnContentThirdSection }{$blog_page.thirdColumnContentThirdSection|cleanHtml nofilter}{/if}
            </div>
          {/if}
        </div>
      </{if $blog_page.headingThirdSection}article{else}div{/if}>
    {/if}
  </section>
{/foreach}