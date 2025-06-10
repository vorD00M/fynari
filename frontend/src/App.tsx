import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Login from './auth/Login';
import Me from './users/Me';
import ProtectedRoute from './layout/ProtectedRoute';
import MainLayout from './layout/MainLayout';
import UserList from './users/List';
import Settings from "./pages/Settings";
import SettingsModules from "./pages/SettingsModules";

function App() {
    return (
        <BrowserRouter>
            <Routes>
                {/* Публичный маршрут */}
                <Route path="/login" element={<Login />} />

                {/* Защищённый маршрут */}
                <Route
                    path="/me"
                    element={
                        <ProtectedRoute>
                            <MainLayout>
                                <Me />
                            </MainLayout>
                        </ProtectedRoute>
                    }
                />

                <Route
                    path="/settings"
                    element={
                        <ProtectedRoute>
                            <MainLayout>
                                <Settings />
                            </MainLayout>
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/settings/modules"
                    element={
                        <ProtectedRoute>
                            <MainLayout>
                                <SettingsModules />
                            </MainLayout>
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/settings/users"
                    element={
                        <ProtectedRoute>
                            <MainLayout>
                                <UserList />
                            </MainLayout>
                        </ProtectedRoute>
                    }
                />


                {/* ⏳ Будущие маршруты:
              /contacts
              /users
              /dashboard */}
            </Routes>
        </BrowserRouter>
    );
}

export default App;
