<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------

namespace swan_test\markdown\block;
use swan\test\sw_test;
use swan\exception\sw_exception;
use mock\markdown\block\sw_block_mock;
use swan\markdown\hash\sw_hash;

/**
+------------------------------------------------------------------------------
* sw_block_test
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller
+------------------------------------------------------------------------------
*/
class sw_block_test extends sw_test
{
	// {{{ members

	/**
	 * block
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $__block = null;

	// }}}
	// {{{ functions
	// {{{ public function setUp()

	/**
	 * setUp
	 *
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		$this->__block = new sw_block_mock();
	}

	// }}}
	// {{{ public function test__hash_html_blocks()

	/**
	 * test__hash_html_blocks
	 *
	 * @access public
	 * @return void
	 */
	public function test__hash_html_blocks()
	{
		$test = <<<EOD


 <div>
	<p>test</p>
	<?php echo "test"; ?>
 </div>


EOD;
		$rev = $this->__block->hash_html_blocks($test);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("\n\n\n\n <div>\n\t<p>test</p>\n\t<?php echo \"test\"; ?>\n </div>\n\n\n\n", $rev);
	}

	// }}}
	// {{{ public function test__do_headers()

	/**
	 * test__do_headers
	 *
	 * @access public
	 * @return void
	 */
	public function test__do_headers()
	{
		$test = <<<EOD
Header1
========

Header2
--------

#Header1

#####Header5

EOD;
		$rev = $this->__block->do_headers($test);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("\n<h1>Header1</h1>\n\n\n<h2>Header2</h2>\n\n\n<h1>Header1</h1>\n\n\n<h5>Header5</h5>\n\n", $rev);
	}

	// }}}
	// {{{ public function test__do_horizontal_rules()

	/**
	 * test__do_horizontal_rules
	 *
	 * @access public
	 * @return void
	 */
	public function test__do_horizontal_rules()
	{
		$test = <<<EOD
- - - - -
EOD;
		$rev = $this->__block->do_horizontal_rules($test);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("\n<hr/>\n", $rev);
	}

	// }}}
	// {{{ public function test__do_code_blocks()

	/**
	 * test__do_code_blocks
	 *
	 * @access public
	 * @return void
	 */
	public function test__do_code_blocks()
	{
		$test = <<<EOD
code_start

	<html>
		<p>
			test
		</p>
	</html>

code_end
EOD;
		$rev = $this->__block->do_code_blocks(str_replace("\t", '    ', $test));
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("code_start\n\n<pre><code>&lt;html&gt;\n    &lt;p&gt;\n        test\n    &lt;/p&gt;\n&lt;/html&gt;\n</code></pre>\n\ncode_end", $rev);
	}

	// }}}
	// {{{ public function test__form_paragraphs()

	/**
	 * test__form_paragraphs
	 *
	 * @access public
	 * @return void
	 */
	public function test__form_paragraphs()
	{
		$test = <<<EOD
- - - - -


test


test1

EOD;
		$rev = $this->__block->do_horizontal_rules($test);
		$rev = $this->__block->form_paragraphs($rev);
		$this->assertEquals("<hr/>\n\n<p>test</p>\n\n<p>test1</p>", $rev);
	}

	// }}}
	// {{{ public function test__do_block_quotes()

	/**
	 * test__do_block_quotes 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__do_block_quotes()
	{
		$test = <<<EOD
> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,
> consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.
>> test
> Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.


EOD;
		$rev = $this->__block->do_block_quotes($test);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("\n<blockquote>\n  <p>This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n  consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.</p>\n  \n  <blockquote>\n    <p>test\n    Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.</p>\n  </blockquote>\n</blockquote>\n\n", $rev);
	}

	// }}}
	// {{{ public function test__do_lists()

	/**
	 * test__do_lists 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function test__do_lists()
	{
		$test = <<<EOD
*   Red
*   Green
*   Blue

EOD;
		$rev = $this->__block->do_lists($test);
		$rev = sw_hash::unhash($rev);
		$rev = $this->assertEquals("\n<ul>\n<li>Red</li>\n<li>Green</li>\n<li>Blue</li>\n</ul>\n\n", $rev);
	}

	// }}}
	// }}}
}
