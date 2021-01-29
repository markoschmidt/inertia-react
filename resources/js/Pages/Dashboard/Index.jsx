import React from 'react';
import Helmet from 'react-helmet';
import { BaseLayout as Layout } from '@/Components/Layouts';

const Dashboard = () => {
  return (
    <div>
      <Helmet>
        <title>Dashboard</title>
      </Helmet>
      <h1 className="mb-8 font-bold text-3xl">Dashboard</h1>
      <p className="mb-12 leading-normal">
        Navigate the app using the main menu
      </p>
    </div>
  );
};

// Persistent layout
// Docs: https://inertiajs.com/pages#persistent-layouts
Dashboard.layout = page => <Layout children={page} />;

export default Dashboard;
