import React from 'react';

export default ({ loading, className, children, disabled, ...props }) => {
  return (
    <button
      disabled={loading || disabled}
      className={`focus:outline-none flex items-center ${className} ${disabled ? "bg-gray-500" : ''}`}
      {...props}
    >
      {loading && <div className="mr-2 btn-spinner" />}
      {children}
    </button>
  );
};
