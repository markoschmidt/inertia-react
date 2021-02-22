module.exports = {
  extends: ['eslint:recommended', 'plugin:react/recommended'],
  parser: 'babel-eslint',
  parserOptions: {
    sourceType: 'module',
    ecmaFeatures: {
      jsx: true
    }
  },
  rules: {
    'no-console': 'off',
    'no-undef': 'off',
    'react/display-name': false,
    'react/prop-types': false,
    'react/jsx-one-expression-per-line': 0,
    'react/jsx-props-no-spreading': 'off',
  }
};
