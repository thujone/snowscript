<?php

class PHPParser_PrettyPrinter_Zend extends PHPParser_PrettyPrinterAbstract
{
    // Special nodes

    public function pParam(PHPParser_Node_Param $node) {
        return ($node->type ? (is_string($node->type) ? $node->type : $this->p($node->type)) . ' ' : '')
             . ($node->byRef ? '&' : '')
             . '$' . $node->name
             . ($node->default ? ' = ' . $this->p($node->default) : '');
    }

    public function pArg(PHPParser_Node_Arg $node) {
        return ($node->byRef ? '&' : '') . $this->p($node->value);
    }

    public function pConst(PHPParser_Node_Const $node) {
        return $node->name . ' = ' . $this->p($node->value);
    }

    // Names

    public function pName(PHPParser_Node_Name $node) {
        return implode('\\', $node->parts);
    }

    public function pName_FullyQualified(PHPParser_Node_Name_FullyQualified $node) {
        return '\\' . implode('\\', $node->parts);
    }

    public function pName_Relative(PHPParser_Node_Name_Relative $node) {
        return 'namespace\\' . implode('\\', $node->parts);
    }

    // Magic Constants

    public function pScalar_ClassConst(PHPParser_Node_Scalar_ClassConst $node) {
        return '__CLASS__';
    }

    public function pScalar_TraitConst(PHPParser_Node_Scalar_TraitConst $node) {
        return '__TRAIT__';
    }

    public function pScalar_DirConst(PHPParser_Node_Scalar_DirConst $node) {
        return '__DIR__';
    }

    public function pScalar_FileConst(PHPParser_Node_Scalar_FileConst $node) {
        return '__FILE__';
    }

    public function pScalar_FuncConst(PHPParser_Node_Scalar_FuncConst $node) {
        return '__FUNCTION__';
    }

    public function pScalar_LineConst(PHPParser_Node_Scalar_LineConst $node) {
        return '__LINE__';
    }

    public function pScalar_MethodConst(PHPParser_Node_Scalar_MethodConst $node) {
        return '__METHOD__';
    }

    public function pScalar_NSConst(PHPParser_Node_Scalar_NSConst $node) {
        return '__NAMESPACE__';
    }

    // Scalars

    public function pScalar_String(PHPParser_Node_Scalar_String $node) {
        return '\'' . $this->pSafe(addcslashes($node->value, '\'\\')) . '\'';
    }

    public function pScalar_Encapsed(PHPParser_Node_Scalar_Encapsed $node) {
        return '"' . $this->pEncapsList($node->parts, '"') . '"';
    }

    public function pScalar_LNumber(PHPParser_Node_Scalar_LNumber $node) {
        return (string) $node->value;
    }

    public function pScalar_DNumber(PHPParser_Node_Scalar_DNumber $node) {
        $stringValue = (string) $node->value;

        // ensure that number is really printed as float
        return ctype_digit($stringValue) ? $stringValue . '.0' : $stringValue;
    }

    // Assignments

    public function pExpr_Assign(PHPParser_Node_Expr_Assign $node) {
        return $this->p($node->var) . ' = ' . $this->p($node->expr);
    }

    public function pExpr_AssignClassProperty(PHPParser_Node_Expr_AssignClassProperty $node) {
        return '$this->' . $node->var->name . ' = ' . $this->p($node->expr);
    }

    public function pExpr_AssignRef(PHPParser_Node_Expr_AssignRef $node) {
        return $this->p($node->var) . ' =& ' . $this->p($node->expr);
    }

    public function pExpr_AssignPlus(PHPParser_Node_Expr_AssignPlus $node) {
        return $this->p($node->var) . ' += ' . $this->p($node->expr);
    }

    public function pExpr_AssignMinus(PHPParser_Node_Expr_AssignMinus $node) {
        return $this->p($node->var) . ' -= ' . $this->p($node->expr);
    }

    public function pExpr_AssignMul(PHPParser_Node_Expr_AssignMul $node) {
        return $this->p($node->var) . ' *= ' . $this->p($node->expr);
    }

    public function pExpr_AssignDiv(PHPParser_Node_Expr_AssignDiv $node) {
        return $this->p($node->var) . ' /= ' . $this->p($node->expr);
    }

    public function pExpr_AssignConcat(PHPParser_Node_Expr_AssignConcat $node) {
        return $this->p($node->var) . ' .= ' . $this->p($node->expr);
    }

    public function pExpr_AssignMod(PHPParser_Node_Expr_AssignMod $node) {
        return $this->p($node->var) . ' %= ' . $this->p($node->expr);
    }

    public function pExpr_AssignBitwiseAnd(PHPParser_Node_Expr_AssignBitwiseAnd $node) {
        return $this->p($node->var) . ' &= ' . $this->p($node->expr);
    }

    public function pExpr_AssignBitwiseOr(PHPParser_Node_Expr_AssignBitwiseOr $node) {
        return $this->p($node->var) . ' |= ' . $this->p($node->expr);
    }

    public function pExpr_AssignBitwiseXor(PHPParser_Node_Expr_AssignBitwiseXor $node) {
        return $this->p($node->var) . ' ^= ' . $this->p($node->expr);
    }

    public function pExpr_AssignShiftLeft(PHPParser_Node_Expr_AssignShiftLeft $node) {
        return $this->p($node->var) . ' <<= ' . $this->p($node->expr);
    }

    public function pExpr_AssignShiftRight(PHPParser_Node_Expr_AssignShiftRight $node) {
        return $this->p($node->var) . ' >>= ' . $this->p($node->expr);
    }

    public function pExpr_AssignList(PHPParser_Node_Expr_AssignList $node) {
        return $this->pAssignList($node->vars) . ' = ' . $this->p($node->expr);
    }

    public function pExpr_AssignListInner($node) {
        return $this->pAssignList($node->vars['items']);
    }

    public function pExpr_Pass($node) {
        return '';
    }

    // Binary expressions

    public function pExpr_Plus(PHPParser_Node_Expr_Plus $node) {
        return $this->p($node->left) . ' + ' . $this->p($node->right);
    }

    public function pExpr_Minus(PHPParser_Node_Expr_Minus $node) {
        return $this->p($node->left) . ' - ' . $this->p($node->right);
    }

    public function pExpr_Mul(PHPParser_Node_Expr_Mul $node) {
        return $this->p($node->left) . ' * ' . $this->p($node->right);
    }

    public function pExpr_Div(PHPParser_Node_Expr_Div $node) {
        return $this->p($node->left) . ' / ' . $this->p($node->right);
    }

    public function pExpr_Concat(PHPParser_Node_Expr_Concat $node) {
        return $this->p($node->left) . ' . ' . $this->p($node->right);
    }

    public function pExpr_Mod(PHPParser_Node_Expr_Mod $node) {
        return $this->p($node->left) . ' % ' . $this->p($node->right);
    }

    public function pExpr_BooleanAnd(PHPParser_Node_Expr_BooleanAnd $node) {
        return $this->p($node->left) . ' && ' . $this->p($node->right);
    }

    public function pExpr_BooleanOr(PHPParser_Node_Expr_BooleanOr $node) {
        return $this->p($node->left) . ' || ' . $this->p($node->right);
    }

    public function pExpr_BitwiseAnd(PHPParser_Node_Expr_BitwiseAnd $node) {
        return $this->p($node->left) . ' & ' . $this->p($node->right);
    }

    public function pExpr_BitwiseOr(PHPParser_Node_Expr_BitwiseOr $node) {
        return $this->p($node->left) . ' | ' . $this->p($node->right);
    }

    public function pExpr_BitwiseXor(PHPParser_Node_Expr_BitwiseXor $node) {
        return $this->p($node->left) . ' ^ ' . $this->p($node->right);
    }

    public function pExpr_ShiftLeft(PHPParser_Node_Expr_ShiftLeft $node) {
        return $this->p($node->left) . ' << ' . $this->p($node->right);
    }

    public function pExpr_ShiftRight(PHPParser_Node_Expr_ShiftRight $node) {
        return $this->p($node->left) . ' >> ' . $this->p($node->right);
    }

    public function pExpr_LogicalAnd(PHPParser_Node_Expr_LogicalAnd $node) {
        return $this->p($node->left) . ' and ' . $this->p($node->right);
    }

    public function pExpr_LogicalOr(PHPParser_Node_Expr_LogicalOr $node) {
        return $this->p($node->left) . ' or ' . $this->p($node->right);
    }

    public function pExpr_LogicalXor(PHPParser_Node_Expr_LogicalXor $node) {
        return $this->p($node->left) . ' xor ' . $this->p($node->right);
    }

    public function pExpr_Equal(PHPParser_Node_Expr_Equal $node) {
        return $this->p($node->left) . ' == ' . $this->p($node->right);
    }

    public function pExpr_NotEqual(PHPParser_Node_Expr_NotEqual $node) {
        return $this->p($node->left) . ' != ' . $this->p($node->right);
    }

    public function pExpr_Identical(PHPParser_Node_Expr_Identical $node) {
        return $this->p($node->left) . ' === ' . $this->p($node->right);
    }

    public function pExpr_NotIdentical(PHPParser_Node_Expr_NotIdentical $node) {
        return $this->p($node->left) . ' !== ' . $this->p($node->right);
    }

    public function pExpr_Greater(PHPParser_Node_Expr_Greater $node) {
        return $this->p($node->left) . ' > ' . $this->p($node->right);
    }

    public function pExpr_GreaterOrEqual(PHPParser_Node_Expr_GreaterOrEqual $node) {
        return $this->p($node->left) . ' >= ' . $this->p($node->right);
    }

    public function pExpr_Smaller(PHPParser_Node_Expr_Smaller $node) {
        return $this->p($node->left) . ' < ' . $this->p($node->right);
    }

    public function pExpr_SmallerOrEqual(PHPParser_Node_Expr_SmallerOrEqual $node) {
        return $this->p($node->left) . ' <= ' . $this->p($node->right);
    }

    public function pExpr_Instanceof(PHPParser_Node_Expr_Instanceof $node) {
        return $this->p($node->expr) . ' instanceof ' . $this->p($node->class);
    }

    // Unary expressions

    public function pExpr_BooleanNot(PHPParser_Node_Expr_BooleanNot $node) {
        return '!' . $this->p($node->expr);
    }

    public function pExpr_BitwiseNot(PHPParser_Node_Expr_BitwiseNot $node) {
        return '~' . $this->p($node->expr);
    }

    public function pExpr_UnaryMinus(PHPParser_Node_Expr_UnaryMinus $node) {
        return '-' . $this->p($node->expr);
    }

    public function pExpr_UnaryPlus(PHPParser_Node_Expr_UnaryPlus $node) {
        return '+' . $this->p($node->expr);
    }

    public function pExpr_PreInc(PHPParser_Node_Expr_PreInc $node) {
        return '++' . $this->p($node->var);
    }

    public function pExpr_PreDec(PHPParser_Node_Expr_PreDec $node) {
        return '--' . $this->p($node->var);
    }

    public function pExpr_PostInc(PHPParser_Node_Expr_PostInc $node) {
        return $this->p($node->var) . '++';
    }

    public function pExpr_PostDec(PHPParser_Node_Expr_PostDec $node) {
        return $this->p($node->var) . '--';
    }

    public function pExpr_ErrorSuppress(PHPParser_Node_Expr_ErrorSuppress $node) {
        return '@' . $this->p($node->expr);
    }

    // Casts

    public function pExpr_Cast_Int(PHPParser_Node_Expr_Cast_Int $node) {
        return '(int) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_Double(PHPParser_Node_Expr_Cast_Double $node) {
        return '(double) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_String(PHPParser_Node_Expr_Cast_String $node) {
        return '(string) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_Array(PHPParser_Node_Expr_Cast_Array $node) {
        return '(array) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_Object(PHPParser_Node_Expr_Cast_Object $node) {
        return '(object) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_Bool(PHPParser_Node_Expr_Cast_Bool $node) {
        return '(bool) ' . $this->p($node->expr);
    }

    public function pExpr_Cast_Unset(PHPParser_Node_Expr_Cast_Unset $node) {
        return '(unset) ' . $this->p($node->expr);
    }

    // Function calls and similar constructs

    public function pExpr_FuncCall(PHPParser_Node_Expr_FuncCall $node) {
        return $this->p($node->name) . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_MethodCall(PHPParser_Node_Expr_MethodCall $node) {
        return $this->pVarOrNewExpr($node->var) . '->' . $this->pObjectProperty($node->name)
             . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_StaticCall(PHPParser_Node_Expr_StaticCall $node) {
        return $this->p($node->class) . '::'
             . ($node->name instanceof PHPParser_Node_Expr
                ? ($node->name instanceof PHPParser_Node_Expr_Variable
                   || $node->name instanceof PHPParser_Node_Expr_ArrayDimFetch
                   ? $this->p($node->name)
                   : '{' . $this->p($node->name) . '}')
                : $node->name)
             . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_Empty(PHPParser_Node_Expr_Empty $node) {
        return 'empty(' . $this->p($node->var) . ')';
    }

    public function pExpr_Isset(PHPParser_Node_Expr_Isset $node) {
        return 'isset(' . $this->pCommaSeparated($node->vars) . ')';
    }

    public function pExpr_Print(PHPParser_Node_Expr_Print $node) {
        return 'print ' . $this->p($node->expr);
    }

    public function pExpr_Eval(PHPParser_Node_Expr_Eval $node) {
        return 'eval(' . $this->p($node->expr) . ')';
    }

    public function pExpr_Include(PHPParser_Node_Expr_Include $node) {
        static $map = array(
            PHPParser_Node_Expr_Include::TYPE_INCLUDE      => 'include',
            PHPParser_Node_Expr_Include::TYPE_INCLUDE_ONCE => 'include_once',
            PHPParser_Node_Expr_Include::TYPE_REQUIRE      => 'require',
            PHPParser_Node_Expr_Include::TYPE_REQUIRE_ONCE => 'require_once',
        );

        return $map[$node->type] . ' ' . $this->p($node->expr);
    }

    // Other

    public function pExpr_Variable(PHPParser_Node_Expr_Variable $node) {
        if ($node->name instanceof PHPParser_Node_Expr) {
            return '${' . $this->p($node->name) . '}';
        } else {
            return '$' . $node->name;
        }
    }

    public function pExpr_Array(PHPParser_Node_Expr_Array $node) {
        return 'array(' . $this->pCommaSeparated($node->items) . ')';
    }

    public function pExpr_ArrayItem(PHPParser_Node_Expr_ArrayItem $node) {
        return (null !== $node->key ? $this->p($node->key) . ' => ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->value);
    }

    public function pExpr_ArrayDimFetch(PHPParser_Node_Expr_ArrayDimFetch $node) {
        return $this->pVarOrNewExpr($node->var)
             . '[' . (null !== $node->dim ? $this->p($node->dim) : '') . ']';
    }

    public function pExpr_ConstFetch(PHPParser_Node_Expr_ConstFetch $node) {
        return $this->p($node->name);
    }

    public function pExpr_ClassConstFetch(PHPParser_Node_Expr_ClassConstFetch $node) {
        return $this->p($node->class) . '::' . $node->name;
    }

    public function pExpr_PropertyFetch(PHPParser_Node_Expr_PropertyFetch $node) {
        return $this->pVarOrNewExpr($node->var) . '->' . $this->pObjectProperty($node->name);
    }

    public function pExpr_StaticPropertyFetch(PHPParser_Node_Expr_StaticPropertyFetch $node) {
        return $this->p($node->class) . '::$' . $this->pObjectProperty($node->name);
    }

    public function pExpr_ShellExec(PHPParser_Node_Expr_ShellExec $node) {
        return '`' . $this->pEncapsList($node->parts, '`') . '`';
    }

    public function pExpr_Closure(PHPParser_Node_Expr_Closure $node) {
        return ($node->static ? 'static ' : '')
             . 'function ' . ($node->byRef ? '&' : '')
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . (!empty($node->uses) ? ' use(' . $this->pCommaSeparated($node->uses) . ')': '')
             . ' {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pExpr_ClosureUse(PHPParser_Node_Expr_ClosureUse $node) {
        return ($node->byRef ? '&' : '') . '$' . $node->var;
    }

    public function pExpr_New(PHPParser_Node_Expr_New $node) {
        return 'new ' . $this->p($node->class) . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_Clone(PHPParser_Node_Expr_Clone $node) {
        return 'clone ' . $this->p($node->expr);
    }

    public function pExpr_Ternary(PHPParser_Node_Expr_Ternary $node) {
        return $this->p($node->cond) . ' ?'
             . (null !== $node->if ? ' ' . $this->p($node->if) . ' ' : '')
             . ': ' . $this->p($node->else);
    }

    public function pExpr_Exit(PHPParser_Node_Expr_Exit $node) {
        return 'die' . (null !== $node->expr ? '(' . $this->p($node->expr) . ')' : '');
    }

    // Declarations

    public function pStmt_Namespace(PHPParser_Node_Stmt_Namespace $node) {
        return 'namespace' . (null !== $node->name ? ' ' . $this->p($node->name) : '')
             . ' {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Use(PHPParser_Node_Stmt_Use $node) {
        return 'use ' . $this->pCommaSeparated($node->uses) . ';';
    }

    public function pStmt_UseUse(PHPParser_Node_Stmt_UseUse $node) {
        return $this->p($node->name)
             . ($node->name->getLast() !== $node->alias ? ' as ' . $node->alias : '');
    }

    public function pStmt_Interface(PHPParser_Node_Stmt_Interface $node) {
        return 'interface ' . $node->name
             . (!empty($node->extends) ? ' extends ' . $this->pCommaSeparated($node->extends) : '')
             . "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Class(PHPParser_Node_Stmt_Class $node) {
        return $this->pModifiers($node->type)
             . 'class ' . $node->name
             . (null !== $node->extends ? ' extends ' . $this->p($node->extends) : '')
             . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
             . "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . 
             $this ->noIndentToken .
             'function __construct(' . 
                (($node->parameter_list) ? $this->pCommaSeparated($node->parameter_list) : '') .
                ') {' . "\n" .
                    $this->pStmts($node->props) .
             "\n" . '}' . "\n" . '}';
    }

    public function pStmt_Trait(PHPParser_Node_Stmt_Trait $node) {
        return 'trait ' . $node->name
             . "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_TraitUse(PHPParser_Node_Stmt_TraitUse $node) {
        return 'use ' . $this->pCommaSeparated($node->traits)
             . (empty($node->adaptations)
                ? ';'
                : ' {' . "\n" . $this->pStmts($node->adaptations) . "\n" . '}');
    }

    public function pStmt_TraitUseAdaptation_Precedence(PHPParser_Node_Stmt_TraitUseAdaptation_Precedence $node) {
        return $this->p($node->trait) . '::' . $node->method
             . ' insteadof ' . $this->pCommaSeparated($node->insteadof) . ';';
    }

    public function pStmt_TraitUseAdaptation_Alias(PHPParser_Node_Stmt_TraitUseAdaptation_Alias $node) {
        return (null !== $node->trait ? $this->p($node->trait) . '::' : '')
             . $node->method . ' as'
             . (null !== $node->newModifier ? ' ' . $this->pModifiers($node->newModifier) : '')
             . (null !== $node->newName     ? ' ' . $node->newName                        : '')
             . ';';
    }

    public function pStmt_PropertyDeclarations(PHPParser_Node_Stmt_PropertyDeclarations $node) {
        $modifiers = $this->pModifiers($node->modifier);
        $result = "";
        foreach ($node->stmts as $stmt) {
            $result .= $modifiers . $this->pCommaSeparated($stmt) . ";\n";
        }
        $result = rtrim($result, "\n");
        return $result;
    }

    public function pStmt_Property(PHPParser_Node_Stmt_Property $node) {
        return $this->pModifiers($node->type) . $this->pCommaSeparated($node->props) . ';';
    }

    public function pStmt_PropertyProperty(PHPParser_Node_Stmt_PropertyProperty $node) {
        return '$' . $node->name
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    public function pStmt_ClassMethod(PHPParser_Node_Stmt_ClassMethod $node) {
        return $this->pModifiers($node->type)
             . 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . (null !== $node->stmts
                ? "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . '}'
                : ';');
    }

    public function pStmt_ClassConst(PHPParser_Node_Stmt_ClassConst $node) {
        return 'const ' . $node->name . ' = ' . $this->pStmts($node->value, false);
    }

    public function pStmt_Function(PHPParser_Node_Stmt_Function $node) {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Const(PHPParser_Node_Stmt_Const $node) {
        return 'const ' . $this->pCommaSeparated($node->consts) . ';';
    }

    public function pStmt_Declare(PHPParser_Node_Stmt_Declare $node) {
        return 'declare (' . $this->pCommaSeparated($node->declares) . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_DeclareDeclare(PHPParser_Node_Stmt_DeclareDeclare $node) {
        return $node->key . ' = ' . $this->p($node->value);
    }

    // Control flow

    public function pStmt_If(PHPParser_Node_Stmt_If $node) {
        return 'if (' . $this->p($node->cond) . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}'
             . $this->pImplode($node->elseifs)
             . (null !== $node->else ? $this->p($node->else) : '');
    }

    public function pStmt_Elseif(PHPParser_Node_Stmt_Elseif $node) {
        return ' elseif (' . $this->p($node->cond) . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Else(PHPParser_Node_Stmt_Else $node) {
        return ' else {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_For(PHPParser_Node_Stmt_For $node) {
        return 'for ('
             . $this->pCommaSeparated($node->init) . ';' . (!empty($node->cond) ? ' ' : '')
             . $this->pCommaSeparated($node->cond) . ';' . (!empty($node->loop) ? ' ' : '')
             . $this->pCommaSeparated($node->loop)
             . ') {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_ForNumeric(PHPParser_Node_Stmt_ForNumeric $node) {
        $is_to = $node->forloop_to === 'to';
        $from = $node->from;
        $to = $node->to;
        $cond = $is_to ? " <= " : " >= ";
        $var = $this->p($node->variable);
        if (!$node->optional_step)
            $inc = $is_to ? "++$var" : "--$var";
        else
            $inc = $is_to ? '$var += ' . $this->p($node->optional_step)
                          : '$var -= ' . $this->p($node->optional_step);
        return
            "for (" . $var . ' = ' . $this->p($from) . '; '
            . $var . $cond . $this->p($to) . '; '
            . $inc
            . ') {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}'
            . "\n" . "unset($var);"
        ;
    }

    public function pStmt_Foreach(PHPParser_Node_Stmt_Foreach $node) {
        return 'foreach (' . $this->p($node->expr) . ' as '
             . (null !== $node->keyVar ? $this->p($node->keyVar) . ' => ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->valueVar) . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}' . "\n"
             . 'unset('
             . (null !== $node->keyVar ? $this->p($node->keyVar) . ', ' : '')
             . $this->p($node->valueVar) . ');'

        ;
    }

    public function pStmt_While(PHPParser_Node_Stmt_While $node) {
        return 'while (' . $this->p($node->cond) . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Do(PHPParser_Node_Stmt_Do $node) {
        return 'do {' . "\n" . $this->pStmts($node->stmts) . "\n"
             . '} while (' . $this->p($node->cond) . ');';
    }

    public function pStmt_Switch(PHPParser_Node_Stmt_Switch $node) {
        return 'switch (' . $this->p($node->cond) . ') {'
             . "\n" . $this->pImplode($node->cases) . '}';
    }

    public function pStmt_TryCatch(PHPParser_Node_Stmt_TryCatch $node) {
        return 'try {' . "\n" . $this->pStmts($node->stmts) . "\n" . '}'
             . $this->pImplode($node->catches);
    }

    public function pStmt_Catch(PHPParser_Node_Stmt_Catch $node) {
        return ' catch (' . $this->p($node->type) . ' $' . $node->var . ') {'
             . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Case(PHPParser_Node_Stmt_Case $node) {
        $result = '';
        foreach ($node->conds as $cond) {
            $case = $this->p($cond, true);
            if ($case != "default") $case = "case $case";
            $result .= "    $case:\n";
        }
        $stmts = $this->pStmts($node->stmts);
        $result .= '    ' . str_replace("\n", "\n    ", $stmts) . "\n";
        $result .= '        break;' . "\n";
        return $result;
    }

    public function pStmt_Break(PHPParser_Node_Stmt_Break $node) {
        return 'break' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
    }

    public function pStmt_Continue(PHPParser_Node_Stmt_Continue $node) {
        return 'continue' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
    }

    public function pStmt_Return(PHPParser_Node_Stmt_Return $node) {
        return 'return' . (null !== $node->expr ? ' ' . $this->p($node->expr) : '') . ';';
    }

    public function pStmt_Throw(PHPParser_Node_Stmt_Throw $node) {
        return 'throw ' . $this->p($node->expr) . ';';
    }

    public function pStmt_Label(PHPParser_Node_Stmt_Label $node) {
        return $node->name . ':';
    }

    public function pStmt_Goto(PHPParser_Node_Stmt_Goto $node) {
        return 'goto ' . $node->name . ';';
    }

    // Other

    public function pStmt_Echo(PHPParser_Node_Stmt_Echo $node) {
        return 'echo ' . $this->pCommaSeparated($node->exprs) . ';';
    }

    public function pStmt_Static(PHPParser_Node_Stmt_Static $node) {
        return 'static ' . $this->pCommaSeparated($node->vars) . ';';
    }

    public function pStmt_Global(PHPParser_Node_Stmt_Global $node) {
        return 'global ' . $this->pCommaSeparated($node->vars) . ';';
    }

    public function pStmt_StaticVar(PHPParser_Node_Stmt_StaticVar $node) {
        return '$' . $node->name
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    public function pStmt_Unset(PHPParser_Node_Stmt_Unset $node) {
        return 'unset(' . $this->pCommaSeparated($node->vars) . ');';
    }

    public function pStmt_InlineHTML(PHPParser_Node_Stmt_InlineHTML $node) {
        return '?>' . $this->pSafe(
            ("\n" === $node->value[0] || "\r" === $node->value[0] ? "\n" : '')
            . $node->value
        ) . '<?php ';
    }

    public function pStmt_HaltCompiler(PHPParser_Node_Stmt_HaltCompiler $node) {
        return '__halt_compiler();' . $node->remaining;
    }

    // Helpers

    public function pObjectProperty($node) {
        if ($node instanceof PHPParser_Node_Expr) {
            return '{' . $this->p($node) . '}';
        } else {
            return $node;
        }
    }

    public function pModifiers($modifiers) {
        return ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_PUBLIC    ? 'public '    : '')
             . ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_PROTECTED ? 'protected ' : '')
             . ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_PRIVATE   ? 'private '   : '')
             . ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_STATIC    ? 'static '    : '')
             . ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_ABSTRACT  ? 'abstract '  : '')
             . ($modifiers & PHPParser_Node_Stmt_Class::MODIFIER_FINAL     ? 'final '     : '');
    }

    public function pEncapsList(array $encapsList, $quote) {
        $return = '';
        foreach ($encapsList as $element) {
            if (is_string($element)) {
                $return .= addcslashes($element, "\n\r\t\f\v$" . $quote . "\\");
            } else {
                $return .= '{' . $this->p($element) . '}';
            }
        }

        return $return;
    }

    public function pAssignList(array $elements) {
        $pAssignList = array();
        foreach ($elements as $element) {
            if (null === $element) {
                $pAssignList[] = '';
            } elseif (is_array($element)) {
                $pAssignList[] = $this->pAssignList($element);
            } else {
                $pAssignList[] = $this->p($element);
            }
        }

        return 'list(' . implode(', ', $pAssignList) . ')';
    }

    public function pVarOrNewExpr(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Expr_New) {
            return '(' . $this->p($node) . ')';
        } else {
            return $this->p($node);
        }
    }
}
